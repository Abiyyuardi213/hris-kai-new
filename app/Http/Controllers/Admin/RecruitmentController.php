<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Models\Candidate;
use App\Models\Application;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    public function index()
    {
        $vacancies = JobVacancy::withCount('applications')->latest()->paginate(10);
        return view('admin.recruitment.index', compact('vacancies'));
    }

    public function create()
    {
        $positions = Jabatan::all();
        return view('admin.recruitment.create', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'position_id' => 'required|exists:positions,id',
            'title' => 'required|string|max:255',
            'description' => 'required',
            'requirements' => 'required',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:draft,open,closed',
            'deadline' => 'nullable|date',
        ]);

        JobVacancy::create($request->all());

        return redirect()->route('admin.recruitment.index')->with('success', 'Lowongan pekerjaan berhasil dibuat.');
    }

    public function show(JobVacancy $recruitment)
    {
        $recruitment->load('applications.candidate');
        return view('admin.recruitment.show', compact('recruitment'));
    }

    public function edit(JobVacancy $recruitment)
    {
        $positions = Jabatan::all();
        return view('admin.recruitment.edit', compact('recruitment', 'positions'));
    }

    public function update(Request $request, JobVacancy $recruitment)
    {
        $request->validate([
            'position_id' => 'required|exists:positions,id',
            'title' => 'required|string|max:255',
            'description' => 'required',
            'requirements' => 'required',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:draft,open,closed',
            'deadline' => 'nullable|date',
        ]);

        $recruitment->update($request->all());

        return redirect()->route('admin.recruitment.index')->with('success', 'Lowongan pekerjaan berhasil diperbarui.');
    }

    public function destroy(JobVacancy $recruitment)
    {
        $recruitment->delete();
        return redirect()->route('admin.recruitment.index')->with('success', 'Lowongan pekerjaan berhasil dihapus.');
    }

    public function applications()
    {
        $applications = Application::with(['jobVacancy', 'candidate'])->latest()->paginate(10);
        return view('admin.recruitment.applications', compact('applications'));
    }

    public function updateApplicationStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewing,interview,test,rejected,hired',
            'admin_notes' => 'nullable|string',
        ]);

        $application->update($request->only(['status', 'admin_notes']));

        return back()->with('success', 'Status lamaran berhasil diperbarui.');
    }
}
