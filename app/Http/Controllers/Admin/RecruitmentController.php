<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Models\Candidate;
use App\Models\Application;
use App\Models\Jabatan;
use App\Models\JobFormation;
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
        return view('admin.recruitment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_lowongan' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:open,closed',
            'description' => 'required',
            'requirements' => 'required',
        ]);

        $vacancy = JobVacancy::create($request->only(['judul_lowongan', 'start_date', 'end_date', 'status']));
        
        $vacancy->detail()->create([
            'description' => $request->description,
            'requirements' => $request->requirements,
        ]);

        return redirect()->route('admin.recruitment.index')->with('success', 'Lowongan pekerjaan berhasil dibuat.');
    }

    public function show(JobVacancy $recruitment)
    {
        $recruitment->load(['detail', 'formations', 'applications.candidate']);
        return view('admin.recruitment.show', compact('recruitment'));
    }

    public function edit(JobVacancy $recruitment)
    {
        $recruitment->load('detail');
        return view('admin.recruitment.edit', compact('recruitment'));
    }

    public function update(Request $request, JobVacancy $recruitment)
    {
        $request->validate([
            'judul_lowongan' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:open,closed',
            'description' => 'required',
            'requirements' => 'required',
        ]);

        $recruitment->update($request->only(['judul_lowongan', 'start_date', 'end_date', 'status']));
        
        $recruitment->detail()->updateOrCreate(
            ['job_vacancy_id' => $recruitment->id],
            [
                'description' => $request->description,
                'requirements' => $request->requirements,
            ]
        );

        return redirect()->route('admin.recruitment.index')->with('success', 'Lowongan pekerjaan berhasil diperbarui.');
    }

    public function destroy(JobVacancy $recruitment)
    {
        $recruitment->delete();
        return redirect()->route('admin.recruitment.index')->with('success', 'Lowongan pekerjaan berhasil dihapus.');
    }

    public function applications(Request $request)
    {
        $query = Application::with(['jobVacancy', 'candidate']);
        
        if ($request->has('vacancy_id')) {
            $query->where('job_vacancy_id', $request->vacancy_id);
        }

        $applications = $query->latest()->paginate(10);
        return view('admin.recruitment.applications', compact('applications'));
    }

    public function showApplicants(JobVacancy $recruitment)
    {
        $applications = Application::with('candidate')
            ->where('job_vacancy_id', $recruitment->id)
            ->latest()
            ->paginate(15);
        return view('admin.recruitment.applicants_list', compact('recruitment', 'applications'));
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

    public function addFormation(Request $request, JobVacancy $recruitment)
    {
        $request->validate([
            'formation_name' => 'required|string',
            'education' => 'required|string',
            'major' => 'required|string',
            'gender' => 'required|string',
            'document_requirements' => 'nullable|string',
        ]);

        $recruitment->formations()->create($request->all());

        return back()->with('success', 'Formasi berhasil ditambahkan.');
    }

    public function updateFormation(Request $request, JobFormation $formation)
    {
        $request->validate([
            'formation_name' => 'required|string',
            'education' => 'required|string',
            'major' => 'required|string',
            'gender' => 'required|string',
            'document_requirements' => 'nullable|string',
        ]);

        $formation->update($request->all());

        return back()->with('success', 'Formasi berhasil diperbarui.');
    }

    public function deleteFormation(JobFormation $formation)
    {
        $vacancy_id = $formation->job_vacancy_id;
        $formation->delete();
        return back()->with('success', 'Formasi berhasil dihapus.');
    }
}
