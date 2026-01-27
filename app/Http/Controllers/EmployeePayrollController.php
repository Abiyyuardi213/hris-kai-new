<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
