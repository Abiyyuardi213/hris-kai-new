<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with('author')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('status')) {
            $query->where('is_active', $request->status);
        }

        $announcements = $query->paginate(10)->withQueryString();

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'image_file' => 'nullable|image|max:2048',
            'attachment_file' => 'nullable|file|max:5120',
            'is_active' => 'required|boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'is_active' => $validated['is_active'],
            'published_at' => $validated['published_at'] ?? now(),
            'author_id' => Auth::id(),
        ];

        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('announcements/images', 'public');
        }

        if ($request->hasFile('attachment_file')) {
            $data['file_attachment'] = $request->file('attachment_file')->store('announcements/attachments', 'public');
        }

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    public function show($id)
    {
        $announcement = Announcement::with('author')->findOrFail($id);
        return view('admin.announcements.show', compact('announcement'));
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'image_file' => 'nullable|image|max:2048',
            'attachment_file' => 'nullable|file|max:5120',
            'is_active' => 'required|boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'is_active' => $validated['is_active'],
            'published_at' => $request->published_at,
        ];

        if ($request->hasFile('image_file')) {
            if ($announcement->image) {
                Storage::delete('public/' . $announcement->image);
            }
            $data['image'] = $request->file('image_file')->store('announcements/images', 'public');
        }

        if ($request->hasFile('attachment_file')) {
            if ($announcement->file_attachment) {
                Storage::delete('public/' . $announcement->file_attachment);
            }
            $data['file_attachment'] = $request->file('attachment_file')->store('announcements/attachments', 'public');
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
