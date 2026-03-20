<?php

namespace App\Http\Controllers;

use App\Models\ProjectPayroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeProjectPayrollController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();

        $projectPayrolls = ProjectPayroll::where('pegawai_id', $employee->id)
            ->latest()
            ->paginate(10);

        return view('employee.project-payroll.index', compact('projectPayrolls'));
    }

    public function show(ProjectPayroll $projectPayroll)
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();

        if ($projectPayroll->pegawai_id !== $employee->id) {
            abort(403);
        }

        return view('employee.project-payroll.show', compact('projectPayroll'));
    }

    public function print($id)
    {
        $projectPayroll = ProjectPayroll::with(['pegawai.jabatan', 'admin'])->findOrFail($id);

        /** @var \App\Models\Pegawai $employee */
        $user = Auth::guard('employee')->user();

        if ($projectPayroll->pegawai_id !== $user->id) {
            abort(403);
        }

        // Standard Signatory (can be customized)
        $mdFinance = \App\Models\Pegawai::whereHas('jabatan', function ($query) {
            $query->where('name', 'like', '%Finance%');
        })->first();

        if (!$mdFinance) {
            $mdFinance = (object)[
                'nama_lengkap' => 'INDARTO PAMOENGKAS',
                'nip' => '654324'
            ];
        }

        $pdf = Pdf::loadView('employee.project-payroll.pdf', compact('projectPayroll', 'mdFinance'))->setPaper('a5');
        return $pdf->stream('slip-project-' . $projectPayroll->pegawai->nip . '-' . $projectPayroll->month . '-' . $projectPayroll->year . '.pdf');
    }
}
