<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Event::where('is_public', true);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $events = $query->latest('start_date')->paginate(10);

        return view('employee.events.index', compact('events'));
    }

    public function show($id)
    {
        $event = \App\Models\Event::where('is_public', true)->findOrFail($id);
        return response()->json($event); // Currently used for modal, or return view if needed
    }
}
