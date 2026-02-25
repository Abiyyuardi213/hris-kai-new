<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeReimbursementController extends Controller
{
    public function index()
    {
        $pegawaiId = Auth::guard('employee')->user()->id;
        $reimbursements = Reimbursement::where('pegawai_id', $pegawaiId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.reimbursements.index', compact('reimbursements'));
    }

    public function create()
    {
        return view('employee.reimbursements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_reimbursement' => 'required|string',
            'tanggal_pengajuan' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'lampiran' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $lampiranPath = $file->store('lampirans', 'public');
        }

        Reimbursement::create([
            'pegawai_id' => Auth::guard('employee')->user()->id,
            'tipe_reimbursement' => $request->tipe_reimbursement,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'lampiran' => $lampiranPath,
            'status' => 'Pending',
        ]);

        return redirect()->route('employee.reimbursements.index')
            ->with('success', 'Pengajuan reimbursement berhasil disubmit.');
    }

    public function show($id)
    {
        $pegawaiId = Auth::guard('employee')->user()->id;
        $reimbursement = Reimbursement::where('id', $id)->where('pegawai_id', $pegawaiId)->firstOrFail();

        return view('employee.reimbursements.show', compact('reimbursement'));
    }
}
