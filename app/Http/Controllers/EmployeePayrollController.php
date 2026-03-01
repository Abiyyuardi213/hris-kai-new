<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeePayrollController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();

        $payrolls = Payroll::where('pegawai_id', $employee->id)
            ->latest()
            ->paginate(10);

        return view('employee.payroll.index', compact('payrolls'));
    }

    public function show(Payroll $payroll)
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();

        if ($payroll->pegawai_id !== $employee->id) {
            abort(403);
        }

        return view('employee.payroll.show', compact('payroll'));
    }

    public function print($id)
    {
        $payroll = Payroll::with(['pegawai.jabatan'])->findOrFail($id);

        /** @var \App\Models\Pegawai $employee */
        $user = Auth::guard('employee')->user();

        if ($payroll->pegawai_id !== $user->id) {
            abort(403);
        }

        $mdFinance = \App\Models\Pegawai::whereHas('jabatan', function ($query) {
            $query->where('name', 'Managing Director of Finance');
        })->first();

        if (!$mdFinance) {
            $mdFinance = (object)[
                'nama_lengkap' => 'INDARTO PAMOENGKAS',
                'nip' => '654324'
            ];
        }

        $pdf = Pdf::loadView('employee.payroll.pdf', compact('payroll', 'mdFinance'))->setPaper('a5');
        return $pdf->stream('slip-gaji-' . $payroll->pegawai->nip . '-' . $payroll->month . '-' . $payroll->year . '.pdf');
    }
}
