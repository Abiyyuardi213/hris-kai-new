<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();

        $overtimes = Overtime::where('pegawai_id', $employee->id)
            ->latest()
            ->paginate(10);

        return view('employee.overtime.index', compact('overtimes'));
    }

    public function create()
    {
        return view('employee.overtime.create');
    }

    public function store(Request $request)
    {
        /** @var \App\Models\Pegawai $employee */
        $employee = Auth::guard('employee')->user();

        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'reason' => 'required|string',
        ]);

        $validated['pegawai_id'] = $employee->id;
        $validated['status'] = 'pending';
        $validated['type'] = 'request';

        Overtime::create($validated);

        return redirect()->route('employee.overtime.index')->with('success', 'Pengajuan lembur berhasil dikirim.');
    }
}
