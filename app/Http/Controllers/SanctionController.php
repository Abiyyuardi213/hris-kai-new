<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SanctionController extends Controller
{
    public function index()
    {
        $sanctions = \App\Models\DisciplinarySanction::where('employee_id', \Illuminate\Support\Facades\Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employee.sanctions.index', compact('sanctions'));
    }

    public function show($id)
    {
        $sanction = \App\Models\DisciplinarySanction::where('employee_id', \Illuminate\Support\Facades\Auth::id())
            ->findOrFail($id);

        return view('employee.sanctions.show', compact('sanction'));
    }

    public function print($id)
    {
        $sanction = \App\Models\DisciplinarySanction::where('employee_id', \Illuminate\Support\Facades\Auth::id())
            ->findOrFail($id);

        $employee = $sanction->employee;
        $vp = null;

        if ($employee->kantor_id) {
            $vp = \App\Models\Pegawai::where('kantor_id', $employee->kantor_id)
                ->whereHas('jabatan', function ($query) {
                    $query->where('name', 'like', '%Vice President%');
                })
                ->first();
        }

        return view('admin.disciplinary_sanctions.print', compact('sanction', 'vp'));
    }
}
