<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\Pegawai;
use App\Models\ShiftKerja;
use App\Services\AttendanceAutoCheckoutService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        app(AttendanceAutoCheckoutService::class)->run();

        $query = Presensi::with([
            'pegawai:id,nip,nama_lengkap,foto',
            'shift:id,name,start_time,end_time',
        ])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc');

        // Filter by date
        if ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('tanggal', '<=', $request->end_date);
        }

        // Filter by employee name/nip
        if ($request->filled('search')) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $presensis = $query->simplePaginate(10)->withQueryString();

        $pegawais = Pegawai::orderBy('nama_lengkap', 'asc')->get();

        return view('admin.presensi.index', compact('presensis', 'pegawais'));
    }

    public function show($id)
    {
        $presensi = Presensi::with(['pegawai', 'shift'])->findOrFail($id);
        return view('admin.presensi.show', compact('presensi'));
    }

    public function create()
    {
        $pegawais = Pegawai::orderBy('nama_lengkap', 'asc')->get();
        return view('admin.presensi.create', compact('pegawais'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpa',
            'keterangan' => 'nullable|string'
        ]);

        $presensi = Presensi::with('shift')->findOrFail($id);
        $data = $request->only(['jam_masuk', 'jam_pulang', 'status', 'keterangan']);

        // Recalculate late and early leave if status is 'Hadir' and times are provided
        if ($data['status'] === 'Hadir' && $presensi->shift) {
            if ($data['jam_masuk']) {
                $checkIn = \Carbon\Carbon::parse($data['jam_masuk']);
                $shiftStart = \Carbon\Carbon::parse($presensi->shift->start_time);

                // Compare only time part using a fixed date to avoid issues
                $checkInTime = \Carbon\Carbon::today()->setTime($checkIn->hour, $checkIn->minute, $checkIn->second);
                $shiftStartTime = \Carbon\Carbon::today()->setTime($shiftStart->hour, $shiftStart->minute, $shiftStart->second);

                $data['terlambat'] = $checkInTime->gt($shiftStartTime) ? abs($checkInTime->diffInMinutes($shiftStartTime)) : 0;
            } else {
                $data['terlambat'] = 0;
            }

            if ($data['jam_pulang']) {
                $checkOut = \Carbon\Carbon::parse($data['jam_pulang']);
                $shiftEnd = \Carbon\Carbon::parse($presensi->shift->end_time);

                $checkOutTime = \Carbon\Carbon::today()->setTime($checkOut->hour, $checkOut->minute, $checkOut->second);
                $shiftEndTime = \Carbon\Carbon::today()->setTime($shiftEnd->hour, $shiftEnd->minute, $shiftEnd->second);

                $data['pulang_cepat'] = $checkOutTime->lt($shiftEndTime) ? abs($shiftEndTime->diffInMinutes($checkOutTime)) : 0;
            } else {
                $data['pulang_cepat'] = 0;
            }

            // Sync dynamic remarks (Keterangan) for 'Hadir' status
            if ($data['terlambat'] > 0) {
                $data['keterangan'] = 'Terlambat ' . $data['terlambat'] . ' menit';
            } else {
                $data['keterangan'] = 'Tepat Waktu';
            }
        } else {
            $data['terlambat'] = 0;
            $data['pulang_cepat'] = 0;
            // If status is Izin/Sakit/Alpa, we keep the admin's manual keterangan
        }

        $presensi->update($data);

        return redirect()->back()->with('success', 'Data presensi berhasil diperbarui');
    }

    public function cleanupPhotos(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|in:HAPUS-FOTO-PRESENSI',
        ], [
            'confirmation.in' => 'Konfirmasi tidak sesuai.',
        ]);

        $deletedFiles = 0;
        $missingFiles = 0;
        $updatedRows = 0;
        $deletedBytes = 0;

        Presensi::where(function ($query) {
                $query->whereNotNull('foto_masuk')
                    ->orWhereNotNull('foto_pulang');
            })
            ->select(['id', 'foto_masuk', 'foto_pulang'])
            ->chunkById(100, function ($presensis) use (&$deletedFiles, &$missingFiles, &$updatedRows, &$deletedBytes) {
                foreach ($presensis as $presensi) {
                    $paths = collect([$presensi->foto_masuk, $presensi->foto_pulang])
                        ->filter()
                        ->unique();

                    foreach ($paths as $path) {
                        if (Storage::disk('public')->exists($path)) {
                            $deletedBytes += Storage::disk('public')->size($path);
                            Storage::disk('public')->delete($path);
                            $deletedFiles++;
                        } else {
                            $missingFiles++;
                        }
                    }

                    $presensi->forceFill([
                        'foto_masuk' => null,
                        'foto_pulang' => null,
                    ])->save();

                    $updatedRows++;
                }
            });

        $message = "Cleanup foto presensi selesai. {$deletedFiles} file dihapus ({$this->formatBytes($deletedBytes)}), {$updatedRows} data presensi diperbarui, {$missingFiles} file sudah tidak ditemukan.";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'deleted_files' => $deletedFiles,
                    'missing_files' => $missingFiles,
                    'updated_rows' => $updatedRows,
                    'deleted_bytes' => $deletedBytes,
                    'deleted_size' => $this->formatBytes($deletedBytes),
                ],
            ]);
        }

        return redirect()
            ->route('admin.presensi.index')
            ->with('success', $message);
    }

    public function cleanupPhotosSummary()
    {
        $rows = 0;
        $files = 0;
        $missingFiles = 0;
        $bytes = 0;

        Presensi::where(function ($query) {
                $query->whereNotNull('foto_masuk')
                    ->orWhereNotNull('foto_pulang');
            })
            ->select(['id', 'foto_masuk', 'foto_pulang'])
            ->chunkById(200, function ($presensis) use (&$rows, &$files, &$missingFiles, &$bytes) {
                foreach ($presensis as $presensi) {
                    $rows++;

                    $paths = collect([$presensi->foto_masuk, $presensi->foto_pulang])
                        ->filter()
                        ->unique();

                    foreach ($paths as $path) {
                        if (Storage::disk('public')->exists($path)) {
                            $files++;
                            $bytes += Storage::disk('public')->size($path);
                        } else {
                            $missingFiles++;
                        }
                    }
                }
            });

        return response()->json([
            'success' => true,
            'data' => [
                'rows' => $rows,
                'files' => $files,
                'missing_files' => $missingFiles,
                'bytes' => $bytes,
                'size' => $this->formatBytes($bytes),
            ],
        ]);
    }

    public function pendingCheckouts()
    {
        $now = Carbon::now('Asia/Jakarta');
        $canValidate = $now->format('H:i:s') >= '22:00:00';

        $presensis = collect();

        if ($canValidate) {
            $presensis = $this->pendingCheckoutQuery($now)
                ->with(['pegawai:id,nip,nama_lengkap', 'shift:id,name,start_time,end_time'])
                ->orderBy('jam_masuk')
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'can_validate' => $canValidate,
                'current_time' => $now->format('H:i:s'),
                'checkout_time' => $now->format('H:i:s'),
                'count' => $presensis->count(),
                'records' => $presensis->map(fn ($presensi) => [
                    'id' => $presensi->id,
                    'pegawai' => [
                        'nip' => $presensi->pegawai?->nip,
                        'nama_lengkap' => $presensi->pegawai?->nama_lengkap,
                    ],
                    'shift' => $presensi->shift?->name,
                    'tanggal' => $presensi->tanggal,
                    'jam_masuk' => $presensi->jam_masuk,
                ])->values(),
            ],
        ]);
    }

    public function validatePendingCheckouts(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');

        if ($now->format('H:i:s') < '22:00:00') {
            return response()->json([
                'success' => false,
                'message' => 'Validasi checkout hanya dapat dilakukan setelah pukul 22:00.',
            ], 422);
        }

        $validated = $request->validate([
            'attendance_ids' => 'nullable|array',
            'attendance_ids.*' => 'string',
        ]);

        $query = $this->pendingCheckoutQuery($now);

        if (!empty($validated['attendance_ids'])) {
            $query->whereIn('id', $validated['attendance_ids']);
        }

        $processed = 0;
        $checkoutTime = $now->format('H:i:s');

        $query->chunkById(100, function ($presensis) use (&$processed, $checkoutTime) {
            foreach ($presensis as $presensi) {
                $presensi->forceFill([
                    'jam_pulang' => $checkoutTime,
                    'pulang_cepat' => 0,
                    'lokasi_pulang' => $presensi->lokasi_pulang ?: 'Checkout divalidasi oleh admin',
                    'keterangan' => $this->adminCheckoutNote($presensi->keterangan),
                ])->save();

                $processed++;
            }
        });

        return response()->json([
            'success' => true,
            'message' => "{$processed} presensi berhasil divalidkan checkout.",
            'data' => [
                'processed' => $processed,
                'checkout_time' => $checkoutTime,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpa',
            'keterangan' => 'nullable|string'
        ]);

        $pegawai = Pegawai::findOrFail($request->pegawai_id);

        $shiftKerjaId = null;
        $dayOfWeek = \Carbon\Carbon::parse($request->tanggal)->locale('id')->isoFormat('dddd');

        $data = $request->only(['pegawai_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status', 'keterangan']);

        $existingPresensi = Presensi::where('pegawai_id', $request->pegawai_id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($existingPresensi) {
            return redirect()->back()->with('error', 'Presensi untuk pegawai ini pada tanggal tersebut sudah ada.');
        }

        if ($data['status'] === 'Hadir') {
            $data['terlambat'] = 0;
            $data['pulang_cepat'] = 0;
            $data['keterangan'] = $data['keterangan'] ?? 'Input Manual';
        } else {
            $data['terlambat'] = 0;
            $data['pulang_cepat'] = 0;
        }

        Presensi::create($data);

        return redirect()->back()->with('success', 'Data presensi berhasil ditambahkan secara manual');
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        $units = ['KB', 'MB', 'GB', 'TB'];
        $size = $bytes / 1024;

        foreach ($units as $unit) {
            if ($size < 1024) {
                return number_format($size, 2) . ' ' . $unit;
            }

            $size /= 1024;
        }

        return number_format($size, 2) . ' PB';
    }

    private function pendingCheckoutQuery(Carbon $now)
    {
        return Presensi::whereDate('tanggal', $now->toDateString())
            ->where('status', 'Hadir')
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_pulang');
    }

    private function adminCheckoutNote(?string $current): string
    {
        $note = 'Checkout divalidasi admin setelah pukul 22:00.';

        if (!$current) {
            return $note;
        }

        if (str_contains($current, 'Checkout divalidasi admin')) {
            return $current;
        }

        return $current . ' | ' . $note;
    }
}
