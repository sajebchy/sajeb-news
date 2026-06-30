<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of tags.
     */
    public function index()
    {
        $tags = Tag::withCount('taggables')
            ->latest()
            ->paginate(20);

        return view('admin.tags.index', [
            'tags' => $tags,
        ]);
    }

    /**
     * Show the form for creating a new tag.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created tag.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:tags',
            'slug' => 'nullable|string|unique:tags',
            'color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'description' => 'nullable|string|max:500',
        ]);

        // Generate slug if not provided
        if (!$validated['slug']) {
            $validated['slug'] = \Str::slug($validated['name']);
        }

        Tag::create($validated);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag created successfully!');
    }

    /**
     * Show the form for editing a tag.
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', [
            'tag' => $tag,
        ]);
    }

    /**
     * Update the specified tag.
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:tags,name,' . $tag->id,
            'slug' => 'nullable|string|unique:tags,slug,' . $tag->id,
            'color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'description' => 'nullable|string|max:500',
        ]);

        // Generate slug if not provided
        if (!$validated['slug']) {
            $validated['slug'] = \Str::slug($validated['name']);
        }

        $tag->update($validated);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag updated successfully!');
    }

    /**
     * Delete the specified tag.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully!');
    }
}
