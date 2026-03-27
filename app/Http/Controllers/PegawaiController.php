<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use App\Models\StatusPegawai;
use App\Models\ShiftKerja;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Kantor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with([
            'statusPegawai',
            'shift',
            'divisi' => function ($q) {
                $q->withoutGlobalScope('office_access');
            },
            'jabatan' => function ($q) {
                $q->withoutGlobalScope('office_access');
            },
            'kantor' => function ($q) {
                $q->withoutGlobalScope('office_access');
            }
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
        }

        if ($request->filled('kantor_id')) {
            $query->where('kantor_id', $request->kantor_id);
        }

        if ($request->filled('status_id')) {
            $query->where('status_pegawai_id', $request->status_id);
        }

        // Sorting logic
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('nama_lengkap', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama_lengkap', 'desc');
                break;
            case 'joined_desc':
                $query->orderBy('tanggal_masuk', 'desc');
                break;
            case 'joined_asc':
                $query->orderBy('tanggal_masuk', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $employees = $query->paginate(10)->withQueryString();
        // Divisi & Jabatan are global master data, should not be scoped by office usage for dropdowns
        $divisions = Divisi::withoutGlobalScope('office_access')->orderBy('name')->get();
        // Kantor can remain scoped or unscoped for filter. Unscoped allows filtering by other offices if user (Super Admin) can see them. 
        // If user is restricted, Pegawai query handles the row visibility.
        $offices = Kantor::withoutGlobalScope('office_access')->orderBy('office_name')->get();
        $statuses = StatusPegawai::orderBy('name')->get();

        return view('employees.index', compact('employees', 'divisions', 'offices', 'statuses'));
    }

    public function create()
    {
        $statuses = StatusPegawai::all();
        $shifts = ShiftKerja::all();
        $divisions = Divisi::withoutGlobalScope('office_access')->get();
        $positions = Jabatan::withoutGlobalScope('office_access')->get();
        // Kantor remains scoped so admin can only assign to their own office(s)
        $offices = Kantor::all();

        // NIP Automation: Starts from 71000
        $lastPegawai = Pegawai::whereRaw("nip REGEXP '^[0-9]+$'")->orderByRaw('CAST(nip AS UNSIGNED) DESC')->first();
        $nextNip = $lastPegawai ? intval($lastPegawai->nip) + 1 : 71000;

        $directorates = \App\Models\Directorate::all();

        return view('employees.create', compact('statuses', 'shifts', 'divisions', 'positions', 'offices', 'nextNip', 'directorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status_pegawai_id' => 'required|exists:employment_statuses,id',
            'sisa_cuti' => 'required|integer|min:0',
            'shift_kerja_id' => 'nullable|exists:shifts,id',
            'divisi_id' => 'nullable|exists:divisions,id',
            'jabatan_id' => 'nullable|exists:positions,id',
            'kantor_id' => 'nullable|exists:offices,id',
            'nip' => 'required|unique:pegawais,nip',
            'nik' => 'required|digits:16|unique:pegawais,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string|max:255',
            'status_pernikahan' => 'nullable|string|max:255',
            'alamat_ktp' => 'nullable|string',
            'alamat_domisili' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'email_pribadi' => 'nullable|email|max:255',
            'tanggal_masuk' => 'required|date',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->filled('foto_cropped')) {
            $imageData = $request->foto_cropped;
            $fileName = 'employees/' . uniqid() . '.jpg';

            // Remove data:image/jpeg;base64,
            $base64Image = substr($imageData, strpos($imageData, ',') + 1);
            Storage::disk('public')->put($fileName, base64_decode($base64Image));
            $validated['foto'] = $fileName;
        } elseif ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('employees', 'public');
            $validated['foto'] = $path;
        }

        Pegawai::create($validated);

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil ditambahkan');
    }

    public function show(Pegawai $employee)

    {
        $employee->load([
            'statusPegawai',
            'shift',
            'divisi' => function ($q) {
                $q->withoutGlobalScope('office_access');
            },
            'jabatan' => function ($q) {
                $q->withoutGlobalScope('office_access');
            },
            'kantor' => function ($q) {
                $q->withoutGlobalScope('office_access');
            }
        ]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Pegawai $employee)

    {
        $statuses = StatusPegawai::all();
        $shifts = ShiftKerja::all();
        $divisions = Divisi::withoutGlobalScope('office_access')->get();
        $positions = Jabatan::withoutGlobalScope('office_access')->get();
        // Kantor remains scoped so admin can only assign to their own office(s)
        $offices = Kantor::all();
        $directorates = \App\Models\Directorate::all();

        return view('employees.edit', compact('employee', 'statuses', 'shifts', 'divisions', 'positions', 'offices', 'directorates'));
    }

    public function update(Request $request, Pegawai $employee)
    {
        $validated = $request->validate([
            'status_pegawai_id' => 'required|exists:employment_statuses,id',
            'sisa_cuti' => 'required|integer|min:0',
            'shift_kerja_id' => 'nullable|exists:shifts,id',
            'divisi_id' => 'nullable|exists:divisions,id',
            'jabatan_id' => 'nullable|exists:positions,id',
            'kantor_id' => 'nullable|exists:offices,id',
            'nip' => 'required|unique:pegawais,nip,' . $employee->id,
            'nik' => 'required|digits:16|unique:pegawais,nik,' . $employee->id,
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string|max:255',
            'status_pernikahan' => 'nullable|string|max:255',
            'alamat_ktp' => 'nullable|string',
            'alamat_domisili' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'email_pribadi' => 'nullable|email|max:255',
            'tanggal_masuk' => 'required|date',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($employee->foto) {
                Storage::disk('public')->delete($employee->foto);
            }
            $path = $request->file('foto')->store('employees', 'public');
            $validated['foto'] = $path;
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil diperbarui');
    }

    public function idCard(Pegawai $employee)
    {
        return view('employees.id-card', compact('employee'));
    }

    public function idCardBack(Pegawai $employee)
    {
        return view('employees.id-card-back', compact('employee'));
    }

    public function destroy(Pegawai $employee)
    {
        if ($employee->foto) {
            Storage::disk('public')->delete($employee->foto);
        }
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil dihapus');
    }

    public function import(Request $request)
    {
        // STEP 1: PREVIEW (Upload file)
        if ($request->hasFile('file')) {
            try {
                $request->validate([
                    'file' => 'required|max:10240' // Remove strict mimes check as it's unreliable for CSV
                ]);

                $file = $request->file('file');
                $extension = strtolower($file->getClientOriginalExtension());

                // Security check for extension
                if (!in_array($extension, ['xlsx', 'xls', 'csv', 'txt'])) {
                    return redirect()->route('employees.index')->with('error', 'Format file tidak didukung. Gunakan Excel atau CSV.');
                }
                // Master data maps for preview names
                $divisiMap = \App\Models\Divisi::all()->pluck('name', 'id')->toArray();
                $positionMap = \App\Models\Jabatan::all()->pluck('name', 'id')->toArray();
                $officeMap = \App\Models\Kantor::all()->pluck('office_name', 'id')->toArray();
                $statusMap = \App\Models\StatusPegawai::all()->pluck('name', 'id')->toArray();
                $shiftMap = \App\Models\ShiftKerja::all()->pluck('name', 'id')->toArray();

                $file = $request->file('file');
                $extension = strtolower($file->getClientOriginalExtension());

                if ($extension === 'csv') {
                    $handle = fopen($file->getRealPath(), 'r');
                    $data = [];
                    $headers = null;
                    $delimiter = ",";

                    $firstLine = fgets($handle);
                    if (str_contains($firstLine, 'sep=')) {
                        $delimiter = trim(str_replace('sep=', '', $firstLine));
                    } else {
                        rewind($handle);
                        // Delimiter detection logic
                        $testLine = fgets($handle);
                        if (substr_count($testLine, ';') > substr_count($testLine, ',')) $delimiter = ';';
                        rewind($handle);
                    }

                    // BOM check
                    $bom = fread($handle, 3);
                    if ($bom !== "\xEF\xBB\xBF") rewind($handle);

                    while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                        if (!$row || (isset($row[0]) && str_contains($row[0], 'sep=')) || empty(array_filter($row))) continue;

                        if (!$headers) {
                            $headers = array_map(function ($h) {
                                return trim(str_replace("\xEF\xBB\xBF", '', $h));
                            }, $row);
                            continue;
                        }

                        if (count($headers) <= count($row)) {
                            $rowData = array_combine(array_slice($headers, 0, count($row)), array_slice($row, 0, count($headers)));
                            $data[] = $rowData;
                        }
                    }
                    fclose($handle);
                } else {
                    $data = (new FastExcel)->import($file)->toArray();
                }

                $previewData = array_map(function ($row) use ($divisiMap, $positionMap, $officeMap, $statusMap, $shiftMap) {
                    return [
                        'nip'               => $row['nip'] ?? '-',
                        'nik'               => $row['nik'] ?? '-',
                        'nama_lengkap'      => $row['nama_lengkap'] ?? '-',
                        'tempat_lahir'      => $row['tempat_lahir'] ?? '-',
                        'tanggal_lahir'     => $this->parseFlexibleDate($row['tanggal_lahir'] ?? null),
                        'jenis_kelamin'     => $row['jenis_kelamin'] ?? 'L',
                        'agama'             => $row['agama'] ?? '-',
                        'status_pernikahan' => $row['status_pernikahan'] ?? 'Lajang',
                        'alamat_ktp'        => $row['alamat_ktp'] ?? '-',
                        'alamat_domisili'   => $row['alamat_domisili'] ?? '-',
                        'no_hp'             => $row['no_hp'] ?? '-',
                        'email_pribadi'     => $row['email'] ?? $row['email_pribadi'] ?? '-',
                        'tanggal_masuk'     => $this->parseFlexibleDate($row['tanggal_masuk'] ?? null) ?? date('Y-m-d'),
                        'sisa_cuti'         => $row['sisa_cuti'] ?? 0,
                        'divisi_id'         => $row['divisi'] ?? null,
                        'jabatan_id'        => $row['jabatan'] ?? null,
                        'kantor_id'         => $row['kantor'] ?? null,
                        'status_pegawai_id' => $row['status_pegawai'] ?? null,
                        'shift_kerja_id'    => $row['shift'] ?? null,
                        'divisi_label'      => $divisiMap[$row['divisi']] ?? $row['divisi'] ?? '-',
                        'jabatan_label'     => $positionMap[$row['jabatan']] ?? $row['jabatan'] ?? '-',
                        'kantor_label'      => $officeMap[$row['kantor']] ?? $row['kantor'] ?? '-',
                        'status_label'      => $statusMap[$row['status_pegawai']] ?? $row['status_pegawai'] ?? '-',
                        'shift_label'       => $shiftMap[$row['shift']] ?? $row['shift'] ?? '-',
                    ];
                }, array_filter($data, fn($r) => !empty($r['nip'])));

                foreach ($previewData as &$row) {
                    $row['exists'] = Pegawai::where('nip', $row['nip'])->orWhere('nik', $row['nik'])->exists();
                }

                return view('employees.import_preview', compact('previewData'));
            } catch (\Exception $e) {
                return redirect()->route('employees.index')->with('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
            }
        }

        // STEP 2: STORE (Confirmed data)
        if ($request->has('employees_data')) {
            $employeesData = json_decode($request->input('employees_data'), true);

            if (!$employeesData || count($employeesData) === 0) {
                return redirect()->route('employees.index')->with('error', 'Tidak ada data untuk diimpor.');
            }

            try {
                $divisiMap = \App\Models\Divisi::all()->pluck('id', 'name')->toArray();
                $positionMap = \App\Models\Jabatan::all()->pluck('id', 'name')->toArray();
                $officeMap = \App\Models\Kantor::all()->pluck('id', 'office_name')->toArray();
                $statusMap = \App\Models\StatusPegawai::all()->pluck('id', 'name')->toArray();
                $shiftMap = \App\Models\ShiftKerja::all()->pluck('id', 'name')->toArray();

                $importedCount = 0;
                foreach ($employeesData as $row) {
                    if (Pegawai::where('nip', $row['nip'])->orWhere('nik', $row['nik'])->exists()) {
                        continue;
                    }

                    Pegawai::create([
                        'nip'               => $row['nip'],
                        'nik'               => $row['nik'],
                        'nama_lengkap'      => $row['nama_lengkap'],
                        'tempat_lahir'      => $row['tempat_lahir'] ?? '-',
                        'tanggal_lahir'     => $row['tanggal_lahir'],
                        'jenis_kelamin'     => strtoupper($row['jenis_kelamin'] ?? 'L'),
                        'agama'             => $row['agama'] ?? '-',
                        'status_pernikahan' => $row['status_pernikahan'] ?? 'Lajang',
                        'alamat_ktp'        => $row['alamat_ktp'] ?? '-',
                        'alamat_domisili'   => $row['alamat_domisili'] ?? '-',
                        'no_hp'             => $row['no_hp'] ?? '-',
                        'email_pribadi'     => ($row['email_pribadi'] != '-') ? $row['email_pribadi'] : null,
                        'tanggal_masuk'     => $row['tanggal_masuk'],
                        'sisa_cuti'         => intval($row['sisa_cuti'] ?? 0),
                        'divisi_id'         => $divisiMap[$row['divisi_id']] ?? $row['divisi_id'] ?? null,
                        'jabatan_id'        => $positionMap[$row['jabatan_id']] ?? $row['jabatan_id'] ?? null,
                        'kantor_id'         => $officeMap[$row['kantor_id']] ?? $row['kantor_id'] ?? null,
                        'status_pegawai_id' => $statusMap[$row['status_pegawai_id']] ?? $row['status_pegawai_id'] ?? null,
                        'shift_kerja_id'    => $shiftMap[$row['shift_kerja_id']] ?? $row['shift_kerja_id'] ?? null,
                    ]);
                    $importedCount++;
                }

                return redirect()->route('employees.index')->with('success', "$importedCount data pegawai berhasil diimpor.");
            } catch (\Exception $e) {
                return redirect()->route('employees.index')->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            }
        }

        return redirect()->route('employees.index')->with('error', 'Permintaan tidak valid.');
    }

    private function parseFlexibleDate($value)
    {
        if (!$value) return null;
        if ($value instanceof \DateTime) return $value->format('Y-m-d');
        
        $value = trim($value);
        if (empty($value) || $value === '-') return null;

        try {
            // Try dd/mm/yyyy format specifically
            if (preg_match('/^\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4}$/', $value)) {
                $separator = str_contains($value, '/') ? '/' : '-';
                return Carbon::createFromFormat("d{$separator}m{$separator}Y", $value)->format('Y-m-d');
            }
            
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'nip',
            'nik',
            'nama_lengkap',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'agama',
            'status_pernikahan',
            'alamat_ktp',
            'alamat_domisili',
            'no_hp',
            'email',
            'tanggal_masuk',
            'sisa_cuti',
            'divisi',
            'jabatan',
            'kantor',
            'status_pegawai',
            'shift'
        ];

        // Example row
        $example = [
            '12345678',
            '3301xxxxxxxxxxxx',
            'Budi Santoso',
            'Jakarta',
            '1990-01-01',
            'L',
            'Islam',
            'Menikah',
            'Jl. Contoh No. 1',
            'Jl. Contoh No. 1',
            '0812xxxxxxxx',
            'budi@example.com',
            '2023-01-01',
            '12',
            '1',
            '1',
            '1',
            '1',
            '1'
        ];

        $callback = function () use ($headers, $example) {
            $file = fopen('php://output', 'w');
            // Force Excel to use comma separator for correct column alignment
            fputs($file, "sep=,\n");
            fputcsv($file, $headers);
            fputcsv($file, $example);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_impor_pegawai.csv"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function export()
    {
        $filename = 'Data-Pegawai-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Headings
            fputcsv($file, [
                'NIP',
                'NIK',
                'Nama Lengkap',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Jenis Kelamin',
                'Agama',
                'Status Pernikahan',
                'Alamat KTP',
                'Alamat Domisili',
                'No HP',
                'Email Pribadi',
                'Tanggal Masuk',
                'Divisi',
                'Jabatan',
                'Kantor',
                'Status Pegawai',
                'Shift',
                'Sisa Cuti'
            ], ';');

            // Data
            Pegawai::with(['statusPegawai', 'shift', 'divisi', 'jabatan', 'kantor'])->chunk(100, function ($employees) use ($file) {
                foreach ($employees as $employee) {
                    fputcsv($file, [
                        $employee->nip,
                        $employee->nik,
                        $employee->nama_lengkap,
                        $employee->tempat_lahir,
                        $employee->tanggal_lahir,
                        $employee->jenis_kelamin,
                        $employee->agama,
                        $employee->status_pernikahan,
                        $employee->alamat_ktp,
                        $employee->alamat_domisili,
                        $employee->no_hp,
                        $employee->email_pribadi,
                        $employee->tanggal_masuk,
                        $employee->divisi_id ?? '-',
                        $employee->jabatan_id ?? '-',
                        $employee->kantor_id ?? '-',
                        $employee->status_pegawai_id ?? '-',
                        $employee->shift_kerja_id ?? '-',
                        $employee->sisa_cuti,
                    ], ';');
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
