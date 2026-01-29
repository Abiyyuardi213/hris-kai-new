<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with('author')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $announcements = $query->paginate(12)->withQueryString();

        return view('employee.announcements.index', compact('announcements'));
    }

    public function show($id)
    {
        $announcement = Announcement::with('author')
            ->where('is_active', true)
            ->findOrFail($id);

        // Get recent announcements for sidebar or recommendations
        $recent = Announcement::where('id', '!=', $id)
            ->where('is_active', true)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return view('employee.announcements.show', compact('announcement', 'recent'));
    }
}
