<?php

namespace App\Http\Controllers;

use App\Models\ShiftPegawai;
use App\Models\ShiftKerja;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class ShiftPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $shifts = ShiftKerja::all();
        $employees = Pegawai::orderBy('nama_lengkap')->get();
        // Simple implementation for now, maybe expanded to a calendar view later
        $employeeShifts = ShiftPegawai::with(['employee', 'shift'])->latest()->paginate(20);

        return view('employee_shifts.index', compact('employeeShifts', 'shifts', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:pegawais,id',
            'shift_id' => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        ShiftPegawai::create($validated);

        // Also update the current shift in employee profile if it's the active one (optional logic)
        $employee = Pegawai::find($request->employee_id);
        $employee->update(['shift_kerja_id' => $request->shift_id]);


        return redirect()->route('employee-shifts.index')->with('success', 'Jadwal shift berhasil ditambahkan');
    }

    public function destroy(ShiftPegawai $employeeShift)
    {
        $employeeShift->delete();
        return redirect()->route('employee-shifts.index')->with('success', 'Jadwal shift dihapus');
    }
}
