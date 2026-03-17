<?php

namespace App\Http\Controllers;

use App\Models\CandidateEducation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CandidateEducationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Candidate $user */
        $user = Auth::guard('candidate')->user();
        $educations = $user->educations()->orderBy('graduation_date', 'desc')->get();
        return view('recruitment.education', compact('educations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'degree_level' => 'required|in:SLTA,D3,D4,S1,S2,S3',
            'major' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'graduation_date' => 'required|date',
            'score' => 'required|numeric|between:0,100',
            'accreditation' => 'required|string|max:10',
        ]);

        /** @var \App\Models\Candidate $user */
        $user = Auth::guard('candidate')->user();
        $user->educations()->create($validated);

        return redirect()->back()->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
    }

    public function destroy(CandidateEducation $education)
    {
        if ($education->candidate_id !== Auth::guard('candidate')->id()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $education->delete();
        return redirect()->back()->with('success', 'Riwayat pendidikan berhasil dihapus.');
    }
}
