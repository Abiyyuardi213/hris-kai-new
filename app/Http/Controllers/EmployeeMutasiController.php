<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MutasiPegawai;
use Illuminate\Support\Facades\Auth;

class EmployeeMutasiController extends Controller
{
    public function index()
    {
        $employee = Auth::guard('employee')->user();

        $mutations = MutasiPegawai::where('employee_id', $employee->id)
            ->with(['fromDivision', 'toDivision', 'fromPosition', 'toPosition', 'fromOffice', 'toOffice'])
            ->latest('mutation_date')
            ->paginate(10);

        return view('employee.mutations.index', compact('employee', 'mutations'));
    }

    public function show($id)
    {
        $employee = Auth::guard('employee')->user();

        $mutation = MutasiPegawai::where('employee_id', $employee->id)
            ->with(['fromDivision', 'toDivision', 'fromPosition', 'toPosition', 'fromOffice', 'toOffice'])
            ->findOrFail($id);

        return view('employee.mutations.show', compact('employee', 'mutation'));
    }
}
