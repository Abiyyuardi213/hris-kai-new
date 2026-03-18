<?php

namespace App\Http\Controllers;

use App\Models\CandidateDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidateDocumentController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Candidate $user */
        $user = Auth::guard('candidate')->user();
        $documents = $user->documents()->get();
        
        $documentTypes = [
            'ktp' => 'KTP',
            'transkip_nilai_uan_slta' => 'Transkip Nilai atau UAN SLTA',
            'akreditasi_d3' => 'Akreditasi D3',
            'transkip_nilai_d3' => 'Transkip Nilai D3',
            'transkip_nilai_s1' => 'Transkip Nilai S1',
            'akreditasi_s1' => 'Akreditasi S1',
            'sertifikat_bahasa_inggris' => 'Sertifikat Bahasa Inggris',
            'ijazah_skl_slta' => 'Ijazah atau SKL SLTA',
            'ijazah_skl_d3' => 'Ijazah atau SKL D3',
            'ijazah_skl_d4_s1' => 'Ijazah atau SKL D4 S1',
        ];

        return view('recruitment.documents', compact('documents', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        /** @var \App\Models\Candidate $user */
        $user = Auth::guard('candidate')->user();

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $fileName = $file->getClientOriginalName();
            $path = $file->store('candidate_documents/' . $user->id, 'public');

            $user->documents()->create([
                'document_type' => $request->document_type,
                'file_path' => $path,
                'file_name' => $fileName,
            ]);

            return redirect()->back()->with('success', 'Dokumen berhasil diunggah.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah dokumen.');
    }

    public function destroy(CandidateDocument $document)
    {
        // Check authorization
        if ($document->candidate_id !== Auth::guard('candidate')->id()) {
            abort(403);
        }

        // Delete from storage
        Storage::disk('public')->delete($document->file_path);
        
        // Delete from database
        $document->delete();

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
