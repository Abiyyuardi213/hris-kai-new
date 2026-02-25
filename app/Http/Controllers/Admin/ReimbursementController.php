<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReimbursementController extends Controller
{
    public function index()
    {
        $reimbursements = Reimbursement::with('pegawai')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reimbursements.index', compact('reimbursements'));
    }

    public function show($id)
    {
        $reimbursement = Reimbursement::with(['pegawai', 'approver'])->findOrFail($id);

        return view('admin.reimbursements.show', compact('reimbursement'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'catatan_approval' => 'nullable|string'
        ]);

        $reimbursement = Reimbursement::findOrFail($id);

        $reimbursement->update([
            'status' => $request->status,
            'catatan_approval' => $request->catatan_approval,
            'approved_by' => Auth::id(),
            'tanggal_approval' => Carbon::now(),
        ]);

        // Notify Employee
        if ($reimbursement->pegawai) {
            $formattedDate = Carbon::parse($reimbursement->tanggal_pengajuan)->translatedFormat('d M Y');
            $reimbursement->pegawai->notify(new \App\Notifications\SystemNotification([
                'title' => 'Status Pengajuan Reimbursement',
                'message' => 'Pengajuan reimbursement Anda bertanggal ' . $formattedDate . ' telah ' . ($request->status == 'Approved' ? 'Disetujui' : 'Ditolak'),
                'url' => route('employee.reimbursements.show', $reimbursement->id),
                'type' => $request->status == 'Approved' ? 'success' : 'danger',
                'icon' => $request->status == 'Approved' ? 'check-circle' : 'x-circle'
            ]));
        }

        return redirect()->route('admin.reimbursements.index')
            ->with('success', 'Reimbursement berhasil diupdate.');
    }

    public function destroy($id)
    {
        $reimbursement = Reimbursement::findOrFail($id);
        $reimbursement->delete();

        return redirect()->route('admin.reimbursements.index')
            ->with('success', 'Reimbursement berhasil dihapus.');
    }
}
