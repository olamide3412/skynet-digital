<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource for Admin.
     */
    public function index()
    {
        $projects = Project::latest()->get();
        return Inertia::render('Admin/Projects/Index', [
            'projects' => $projects
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Projects/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'demo_url' => 'nullable|url|max:500',
            'image_path' => 'nullable|image|max:2048', // 2MB Max
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('projects', 'public');
        }

        Project::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource for Public Portfolio.
     */
    public function portfolio()
    {
        $projects = Project::latest()->get();
        return Inertia::render('Portfolio', [
            'projects' => $projects
        ]);
    }

    /**
     * Display the specified resource for Public Portfolio Show Page.
     */
    public function show(Project $project)
    {
        $meta = [
            'title' => $project->title . ' | Skynet Digital Limited',
            'description' => substr(strip_tags($project->description ?? 'Details about ' . $project->title . ' by Skynet Digital Limited.'), 0, 160) . '...',
            'image' => $project->image_path ? asset('storage/' . $project->image_path) : asset('images/logo.png'),
            'type' => 'article',
        ];

        return Inertia::render('Portfolio/Show', [
            'project' => $project
        ])->withViewData(['meta' => $meta]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return Inertia::render('Admin/Projects/Edit', [
            'project' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'demo_url' => 'nullable|url|max:500',
            'image_path' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image_path')) {
            // Delete old image
            if ($project->image_path) {
                Storage::disk('public')->delete($project->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('projects', 'public');
        }

        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->image_path) {
            Storage::disk('public')->delete($project->image_path);
        }
        
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
}
