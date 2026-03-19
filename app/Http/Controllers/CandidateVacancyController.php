<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CandidateVacancyController extends Controller
{
    public function index()
    {
        $now = Carbon::now()->toDateString();
        $vacancies = JobVacancy::where('status', 'open')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->withCount('applications')
            ->latest()
            ->paginate(10);

        return view('recruitment.vacancies.index', compact('vacancies'));
    }

    public function show(JobVacancy $vacancy)
    {
        $vacancy->load(['detail', 'formations']);
        return view('recruitment.vacancies.show', compact('vacancy'));
    }
}
