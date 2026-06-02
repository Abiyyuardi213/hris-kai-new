<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\DisciplinarySanction;
use App\Models\Event;
use App\Models\IzinPegawai;
use App\Models\MutasiPegawai;
use App\Models\Offboarding;
use App\Models\Overtime;
use App\Models\Payroll;
use App\Models\Pegawai;
use App\Models\PerformanceAppraisal;
use App\Models\PerjalananDinas;
use App\Models\PerjalananDinasPeserta;
use App\Models\Presensi;
use App\Models\ProjectPayroll;
use App\Models\Reimbursement;
use App\Models\ShiftPegawai;
use App\Models\User;
use App\Notifications\SystemNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeApiController extends Controller
{
    public function dashboard(Request $request)
    {
        $pegawai = $this->employee($request)->load(['jabatan', 'divisi', 'kantor', 'statusPegawai', 'shift']);
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $monthlyAttendance = Presensi::where('pegawai_id', $pegawai->id)
            ->whereMonth('tanggal', Carbon::now('Asia/Jakarta')->month)
            ->whereYear('tanggal', Carbon::now('Asia/Jakarta')->year)
            ->get();

        return $this->success([
            'pegawai' => $pegawai,
            'today_attendance' => Presensi::with('shift')
                ->where('pegawai_id', $pegawai->id)
                ->whereDate('tanggal', $today)
                ->first(),
            'today_shift' => $this->getTodayShift($pegawai),
            'attendance_stats' => $this->attendanceStats($monthlyAttendance),
            'announcements' => $this->activeAnnouncements()->limit(3)->get(),
            'upcoming_events' => Event::where('is_public', true)
                ->where('start_date', '>=', Carbon::now('Asia/Jakarta')->startOfDay())
                ->orderBy('start_date')
                ->limit(5)
                ->get(),
            'unread_notifications_count' => $pegawai->unreadNotifications()->count(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $pegawai = $this->employee($request);

        $validated = $request->validate([
            'nama_lengkap' => ['sometimes', 'required', 'string', 'max:255'],
            'email_pribadi' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:15'],
            'alamat_domisili' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'foto_base64' => ['nullable', 'string'],
        ]);

        $data = collect($validated)->only([
            'nama_lengkap',
            'email_pribadi',
            'no_hp',
            'alamat_domisili',
        ])->toArray();

        if ($request->hasFile('foto')) {
            $this->deletePublicFile($pegawai->foto);
            $data['foto'] = $request->file('foto')->store('profiles', 'public');
        } elseif ($request->filled('foto_base64')) {
            $this->deletePublicFile($pegawai->foto);
            $data['foto'] = $this->storeBase64Image($request->foto_base64, 'profiles');
        }

        $pegawai->update($data);

        return $this->success($pegawai->fresh(['divisi', 'jabatan', 'shift', 'kantor', 'statusPegawai']), 'Profil berhasil diperbarui.');
    }

    public function shifts(Request $request)
    {
        $query = ShiftPegawai::with('shift')
            ->where('employee_id', $this->employee($request)->id)
            ->orderByDesc('start_date');

        return $this->success($query->paginate($request->integer('per_page', 10)));
    }

    public function izinIndex(Request $request)
    {
        return $this->success(
            IzinPegawai::with('admin:id,name,email')
                ->where('pegawai_id', $this->employee($request)->id)
                ->latest()
                ->paginate($request->integer('per_page', 10))
        );
    }

    public function izinStore(Request $request)
    {
        $pegawai = $this->employee($request);

        $validated = $request->validate([
            'type' => ['required', Rule::in(['izin', 'sakit', 'cuti', 'dispensasi', 'lainnya'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($validated['type'] === 'cuti') {
            $days = Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) + 1;
            if ($pegawai->sisa_cuti < $days) {
                return $this->error("Sisa cuti tidak mencukupi. Sisa: {$pegawai->sisa_cuti}, pengajuan: {$days}.", 422);
            }
        }

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('izin_attachments', 'public');
        }

        $izin = IzinPegawai::create($validated + [
            'pegawai_id' => $pegawai->id,
            'status' => 'pending',
        ]);

        $this->notifyAdmins('Pengajuan Izin Baru', "{$pegawai->nama_lengkap} mengajukan {$izin->type}", 'file-text');

        return $this->success($izin, 'Pengajuan izin berhasil dikirim.', 201);
    }

    public function izinShow(Request $request, string $id)
    {
        return $this->success(
            IzinPegawai::with('admin:id,name,email')
                ->where('pegawai_id', $this->employee($request)->id)
                ->findOrFail($id)
        );
    }

    public function overtimeIndex(Request $request)
    {
        return $this->success(
            Overtime::with('admin:id,name,email')
                ->where('pegawai_id', $this->employee($request)->id)
                ->latest()
                ->paginate($request->integer('per_page', 10))
        );
    }

    public function overtimeStore(Request $request)
    {
        $pegawai = $this->employee($request);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'reason' => ['required', 'string'],
        ]);

        $overtime = Overtime::create($validated + [
            'pegawai_id' => $pegawai->id,
            'status' => 'pending',
            'type' => 'request',
        ]);

        $this->notifyAdmins('Pengajuan Lembur Baru', "{$pegawai->nama_lengkap} mengajukan lembur tanggal {$overtime->date}", 'clock', 'warning');

        return $this->success($overtime, 'Pengajuan lembur berhasil dikirim.', 201);
    }

    public function overtimeShow(Request $request, string $id)
    {
        return $this->success(
            Overtime::with('admin:id,name,email')
                ->where('pegawai_id', $this->employee($request)->id)
                ->findOrFail($id)
        );
    }

    public function payrollIndex(Request $request)
    {
        return $this->success(
            Payroll::where('pegawai_id', $this->employee($request)->id)
                ->latest()
                ->paginate($request->integer('per_page', 10))
        );
    }

    public function payrollShow(Request $request, string $id)
    {
        return $this->success(
            Payroll::with('admin:id,name,email')
                ->where('pegawai_id', $this->employee($request)->id)
                ->findOrFail($id)
        );
    }

    public function projectPayrollIndex(Request $request)
    {
        return $this->success(
            ProjectPayroll::where('pegawai_id', $this->employee($request)->id)
                ->latest()
                ->paginate($request->integer('per_page', 10))
        );
    }

    public function projectPayrollShow(Request $request, string $id)
    {
        return $this->success(
            ProjectPayroll::with('admin:id,name,email')
                ->where('pegawai_id', $this->employee($request)->id)
                ->findOrFail($id)
        );
    }

    public function reimbursementIndex(Request $request)
    {
        return $this->success(
            Reimbursement::with('approver:id,name,email')
                ->where('pegawai_id', $this->employee($request)->id)
                ->orderByDesc('created_at')
                ->paginate($request->integer('per_page', 10))
        );
    }

    public function reimbursementStore(Request $request)
    {
        $pegawai = $this->employee($request);

        $validated = $request->validate([
            'tipe_reimbursement' => ['required', 'string'],
            'tanggal_pengajuan' => ['required', 'date'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        if ($request->hasFile('lampiran')) {
            $validated['lampiran'] = $request->file('lampiran')->store('lampirans', 'public');
        }

        $reimbursement = Reimbursement::create($validated + [
            'pegawai_id' => $pegawai->id,
            'status' => 'Pending',
        ]);

        $this->notifyAdmins('Pengajuan Reimbursement Baru', "{$pegawai->nama_lengkap} mengajukan reimbursement.", 'receipt');

        return $this->success($reimbursement, 'Pengajuan reimbursement berhasil dikirim.', 201);
    }

    public function reimbursementShow(Request $request, string $id)
    {
        return $this->success(
            Reimbursement::with('approver:id,name,email')
                ->where('pegawai_id', $this->employee($request)->id)
                ->findOrFail($id)
        );
    }

    public function offboardingIndex(Request $request)
    {
        return $this->success(
            Offboarding::with('processor:id,name,email')
                ->where('pegawai_id', $this->employee($request)->id)
                ->orderByDesc('created_at')
                ->paginate($request->integer('per_page', 10))
        );
    }

    public function offboardingStore(Request $request)
    {
        $pegawai = $this->employee($request);

        $hasPending = Offboarding::where('pegawai_id', $pegawai->id)
            ->whereIn('status', ['Pending', 'In Progress'])
            ->exists();

        if ($hasPending) {
            return $this->error('Anda masih memiliki pengajuan offboarding yang sedang diproses.', 422);
        }

        $validated = $request->validate([
            'tipe_offboarding' => ['required', 'string'],
            'tanggal_efektif' => ['required', 'date', 'after_or_equal:today'],
            'alasan_keluar' => ['required', 'string', 'max:1000'],
            'saran_masukan' => ['required', 'string', 'max:1000'],
        ]);

        $offboarding = Offboarding::create($validated + [
            'pegawai_id' => $pegawai->id,
            'status' => 'Pending',
        ]);

        $this->notifyAdmins('Pengajuan Offboarding Baru', "{$pegawai->nama_lengkap} mengajukan {$offboarding->tipe_offboarding}.", 'log-out', 'warning');

        return $this->success($offboarding, 'Pengajuan offboarding berhasil dikirim.', 201);
    }

    public function offboardingShow(Request $request, string $id)
    {
        return $this->success(
            Offboarding::with('processor:id,name,email')
                ->where('pegawai_id', $this->employee($request)->id)
                ->findOrFail($id)
        );
    }

    public function tripIndex(Request $request)
    {
        $pegawai = $this->employee($request);

        return $this->success(
            PerjalananDinas::with(['pemohon:id,nip,nama_lengkap', 'pengetuju:id,name,email'])
                ->where(function ($query) use ($pegawai) {
                    $query->where('pegawai_id', $pegawai->id)
                        ->orWhereHas('peserta', fn ($q) => $q->where('pegawai_id', $pegawai->id));
                })
                ->orderByDesc('created_at')
                ->paginate($request->integer('per_page', 10))
        );
    }

    public function tripStore(Request $request)
    {
        $pegawai = $this->employee($request);

        $validated = $request->validate([
            'tujuan' => ['required', 'string'],
            'keperluan' => ['required', 'string'],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'jenis_transportasi' => ['nullable', 'string'],
            'estimasi_biaya' => ['required', 'numeric', 'min:0'],
        ]);

        $trip = DB::transaction(function () use ($validated, $pegawai) {
            $trip = PerjalananDinas::create($validated + [
                'pegawai_id' => $pegawai->id,
                'status' => 'Pengajuan',
            ]);

            PerjalananDinasPeserta::create([
                'perjalanan_dinas_id' => $trip->id,
                'pegawai_id' => $pegawai->id,
            ]);

            return $trip;
        });

        $this->notifyAdmins('Pengajuan Perjalanan Dinas Baru', "{$pegawai->nama_lengkap} mengajukan perjalanan dinas ke {$trip->tujuan}.", 'briefcase');

        return $this->success($trip->load('pegawaiPeserta:id,nip,nama_lengkap'), 'Pengajuan perjalanan dinas berhasil dikirim.', 201);
    }

    public function tripShow(Request $request, string $id)
    {
        $pegawai = $this->employee($request);

        return $this->success(
            PerjalananDinas::with(['pemohon', 'pegawaiPeserta', 'pengetuju:id,name,email'])
                ->where(function ($query) use ($pegawai) {
                    $query->where('pegawai_id', $pegawai->id)
                        ->orWhereHas('peserta', fn ($q) => $q->where('pegawai_id', $pegawai->id));
                })
                ->findOrFail($id)
        );
    }

    public function performanceIndex(Request $request)
    {
        return $this->success(
            PerformanceAppraisal::with('appraiser:id,name,email')
                ->where('pegawai_id', $this->employee($request)->id)
                ->where('status', 'Selesai')
                ->orderByDesc('tahun')
                ->paginate($request->integer('per_page', 10))
        );
    }

    public function performanceShow(Request $request, string $id)
    {
        return $this->success(
            PerformanceAppraisal::with(['pegawai.jabatan', 'pegawai.divisi', 'appraiser:id,name,email', 'items.indicator'])
                ->where('pegawai_id', $this->employee($request)->id)
                ->where('status', 'Selesai')
                ->findOrFail($id)
        );
    }

    public function announcementIndex(Request $request)
    {
        $query = $this->activeAnnouncements();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        return $this->success($query->paginate($request->integer('per_page', 10)));
    }

    public function announcementShow(string $id)
    {
        $announcement = $this->activeAnnouncements()->findOrFail($id);

        return $this->success([
            'announcement' => $announcement,
            'recent' => $this->activeAnnouncements()
                ->where('id', '!=', $announcement->id)
                ->limit(5)
                ->get(),
        ]);
    }

    public function mutationIndex(Request $request)
    {
        return $this->success(
            MutasiPegawai::with(['fromDivision', 'toDivision', 'fromPosition', 'toPosition', 'fromOffice', 'toOffice'])
                ->where('employee_id', $this->employee($request)->id)
                ->latest('mutation_date')
                ->paginate($request->integer('per_page', 10))
        );
    }

    public function mutationShow(Request $request, string $id)
    {
        return $this->success(
            MutasiPegawai::with(['employee', 'fromDivision', 'toDivision', 'fromPosition', 'toPosition', 'fromOffice', 'toOffice'])
                ->where('employee_id', $this->employee($request)->id)
                ->findOrFail($id)
        );
    }

    public function sanctionIndex(Request $request)
    {
        return $this->success(
            DisciplinarySanction::where('employee_id', $this->employee($request)->id)
                ->orderByDesc('created_at')
                ->paginate($request->integer('per_page', 10))
        );
    }

    public function sanctionShow(Request $request, string $id)
    {
        return $this->success(
            DisciplinarySanction::where('employee_id', $this->employee($request)->id)
                ->findOrFail($id)
        );
    }

    public function eventIndex(Request $request)
    {
        $query = Event::where('is_public', true);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return $this->success($query->latest('start_date')->paginate($request->integer('per_page', 10)));
    }

    public function eventShow(string $id)
    {
        return $this->success(Event::where('is_public', true)->findOrFail($id));
    }

    public function insurance(Request $request)
    {
        $pegawai = $this->employee($request);

        return $this->success([
            'plan_name' => 'MyCare Ultimate',
            'yearly_premium' => 1593000,
            'benefits' => [
                'Rawat Inap',
                'Hospital Income (Rp 500,000)',
                'Hospital Cash Plan (Rp 500,000)',
                'Ambulans (Rp 1,000,000)',
                'Evakuasi & Repatriasi Medis',
            ],
            'card_number' => 'MI-' . str_pad((string) crc32($pegawai->id), 8, '0', STR_PAD_LEFT),
            'effective_date' => $pegawai->tanggal_masuk
                ? Carbon::parse($pegawai->tanggal_masuk)->toDateString()
                : now()->subMonths(6)->format('Y-m-d'),
            'pegawai' => $pegawai->loadMissing(['jabatan', 'divisi', 'kantor']),
        ]);
    }

    public function notifications(Request $request)
    {
        return $this->success(
            $this->employee($request)
                ->notifications()
                ->latest()
                ->paginate($request->integer('per_page', 15))
        );
    }

    public function markNotificationAsRead(Request $request, string $id)
    {
        $notification = $this->employee($request)->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return $this->success($notification->fresh(), 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllNotificationsAsRead(Request $request)
    {
        $this->employee($request)->unreadNotifications->markAsRead();

        return $this->success(null, 'Semua notifikasi ditandai sudah dibaca.');
    }

    private function employee(Request $request): Pegawai
    {
        /** @var Pegawai $pegawai */
        $pegawai = $request->user();

        return $pegawai;
    }

    private function activeAnnouncements()
    {
        return Announcement::with('author:id,name,email')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');
    }

    private function getTodayShift(Pegawai $pegawai)
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $scheduledShift = ShiftPegawai::where('employee_id', $pegawai->id)
            ->whereDate('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereDate('end_date', '>=', $today)
                    ->orWhereNull('end_date');
            })
            ->with('shift')
            ->first();

        return $scheduledShift?->shift ?? $pegawai->shift;
    }

    private function attendanceStats($records): array
    {
        return [
            'total_hadir' => $records->where('status', 'Hadir')->count(),
            'total_late' => $records->where('terlambat', '>', 0)->count(),
            'total_early' => $records->where('pulang_cepat', '>', 0)->count(),
            'total_izin' => $records->where('status', 'Izin')->count(),
            'total_sakit' => $records->where('status', 'Sakit')->count(),
        ];
    }

    private function storeBase64Image(string $image, string $directory): string
    {
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
        $image = str_replace(' ', '+', $image);
        $fileName = trim($directory, '/') . '/' . uniqid('', true) . '.jpg';

        Storage::disk('public')->put($fileName, base64_decode($image));

        return $fileName;
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function notifyAdmins(string $title, string $message, string $icon, string $type = 'info'): void
    {
        User::query()->each(function (User $admin) use ($title, $message, $icon, $type) {
            $admin->notify(new SystemNotification([
                'title' => $title,
                'message' => $message,
                'url' => '#',
                'type' => $type,
                'icon' => $icon,
            ]));
        });
    }

    private function success($data = null, string $message = 'OK', int $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    private function error(string $message, int $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
