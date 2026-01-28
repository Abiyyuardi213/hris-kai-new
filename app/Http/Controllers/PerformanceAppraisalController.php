<?php

namespace App\Http\Controllers;

use App\Models\PerformanceAppraisal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceAppraisalController extends Controller
{
    public function index()
    {
        $employee = Auth::guard('employee')->user();
        $appraisals = PerformanceAppraisal::where('pegawai_id', $employee->id)
            ->where('status', 'Selesai')
            ->orderBy('tahun', 'desc')
            ->paginate(10);

        return view('employee.performance.index', compact('appraisals'));
    }

    public function show($id)
    {
        $employee = Auth::guard('employee')->user();
        $appraisal = PerformanceAppraisal::with(['pegawai.jabatan', 'pegawai.divisi', 'appraiser', 'items.indicator'])
            ->where('pegawai_id', $employee->id)
            ->where('status', 'Selesai')
            ->findOrFail($id);

        return view('employee.performance.show', compact('appraisal'));
    }

    public function print($id)
    {
        $employee = Auth::guard('employee')->user();
        $appraisal = PerformanceAppraisal::with(['pegawai.jabatan', 'pegawai.divisi', 'appraiser', 'items.indicator'])
            ->where('pegawai_id', $employee->id)
            ->where('status', 'Selesai')
            ->findOrFail($id);

        return view('admin.performance.print', compact('appraisal'));
    }
}
