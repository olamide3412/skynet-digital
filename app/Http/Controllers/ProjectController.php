<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnums;
use App\Models\Log;
use App\Models\Project;
use App\Models\ProjectMedia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource for Admin.
     */
    public function index()
    {
        $projects = Project::with('user')->latest()->get();
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
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'demo_url' => 'nullable|url|max:500',
            'image_path' => 'nullable|image|max:2048', // 2MB Max
            'is_published' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'media_files.*' => 'nullable|file|max:20480', // 20MB Max per file
            'media_urls' => 'nullable|array',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('projects', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['user_id'] = auth()->id();

        $project = Project::create($validated);

        // Log action
        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Created project post: "' . $project->title . '" (ID: ' . $project->id . ')'
        ]);

        // Process File Media Uploads
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $mime = $file->getMimeType();
                $type = str_starts_with($mime, 'video/') ? 'video' : 'image';
                $path = $file->store('project_media', 'public');

                $project->media()->create([
                    'type' => $type,
                    'source_type' => 'file',
                    'file_path' => $path,
                ]);
            }
        }

        // Process External Media URLs
        if ($request->has('media_urls') && is_array($request->input('media_urls'))) {
            foreach ($request->input('media_urls') as $item) {
                if (is_array($item) && !empty($item['url'])) {
                    $project->media()->create([
                        'type' => $item['type'] ?? 'image',
                        'source_type' => 'url',
                        'url' => $item['url'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource for Public Our Work.
     */
    public function ourWork()
    {
        $projects = Project::where('is_published', true)->with(['media', 'user'])->latest()->get();
        return Inertia::render('OurWork', [
            'projects' => $projects
        ]);
    }

    /**
     * Display the specified resource for Public Our Work Show Page.
     */
    public function show(Project $project)
    {
        if (!$project->is_published && !auth()->check()) {
            abort(404);
        }

        $project->load(['media', 'user']);

        $title = $project->meta_title ?: ($project->title . ' | Skynet Digital Limited');
        $rawDesc = $project->meta_description ?: strip_tags($project->description ?? '');
        $description = Str::limit(trim(preg_replace('/\s+/', ' ', $rawDesc)), 160);
        $image = $project->image_path ? asset('storage/' . $project->image_path) : asset('images/logo.png');
        $url = route('our-work.show', $project->id);
        $keywords = $project->meta_keywords ?: implode(', ', array_filter([$project->category, $project->service, $project->industry, 'Skynet Digital Limited', 'Case Study']));

        $meta = [
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'url' => $url,
            'type' => 'article',
            'keywords' => $keywords,
        ];

        return Inertia::render('OurWork/Show', [
            'project' => $project,
            'meta' => $meta,
        ])->withViewData(['meta' => $meta]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $user = auth()->user();
        $isSuperAdmin = $user?->role === RoleEnums::SuperAdministrator->value;
        $isOwner = $project->user_id === null || $project->user_id === $user?->id;

        if (!$isSuperAdmin && !$isOwner) {
            return redirect()->route('admin.projects.index')->with('error', 'Unauthorized. Only the project owner or a Super Administrator can edit this project post.');
        }

        $project->load('media');

        // Pass staff user list to super admin for owner reassignment
        $users = [];
        if ($isSuperAdmin) {
            $users = User::select('id', 'name', 'email', 'role')
                ->whereIn('role', [
                    RoleEnums::Staff->value,
                    RoleEnums::Administrator->value,
                    RoleEnums::SuperAdministrator->value,
                ])
                ->orderBy('name')
                ->get();
        }

        return Inertia::render('Admin/Projects/Edit', [
            'project' => $project,
            'isSuperAdmin' => $isSuperAdmin,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $user = auth()->user();
        $isSuperAdmin = $user?->role === RoleEnums::SuperAdministrator->value;
        $isOwner = $project->user_id === null || $project->user_id === $user?->id;

        if (!$isSuperAdmin && !$isOwner) {
            return redirect()->route('admin.projects.index')->with('error', 'Unauthorized. Only the project owner or a Super Administrator can update this project post.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'service' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'demo_url' => 'nullable|url|max:500',
            'image_path' => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'media_files.*' => 'nullable|file|max:20480',
            'media_urls' => 'nullable|array',
            'removed_media_ids' => 'nullable|array',
            'user_id' => 'nullable|exists:users,id', // Only super admin can reassign
        ]);

        if ($request->hasFile('image_path')) {
            if ($project->image_path) {
                Storage::disk('public')->delete($project->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('projects', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published', true);

        // Only super admin can reassign project owner
        if (!$isSuperAdmin) {
            unset($validated['user_id']);
        }

        $oldOwnerId = $project->user_id;
        $project->update($validated);

        // Build log message
        $logMessage = 'Updated project post: "' . $project->title . '" (ID: ' . $project->id . ')';
        if ($isSuperAdmin && isset($validated['user_id']) && $validated['user_id'] != $oldOwnerId) {
            $newOwner = User::find($validated['user_id']);
            $logMessage .= ' — Ownership reassigned to: ' . ($newOwner?->name ?? 'Unknown');
        }

        // Log action
        Log::create([
            'user_id' => auth()->id(),
            'log' => $logMessage,
        ]);

        // Remove Deleted Media Items
        if ($request->has('removed_media_ids') && is_array($request->input('removed_media_ids'))) {
            $mediaToRemove = ProjectMedia::whereIn('id', $request->input('removed_media_ids'))
                ->where('project_id', $project->id)
                ->get();

            foreach ($mediaToRemove as $m) {
                if ($m->source_type === 'file' && $m->file_path) {
                    Storage::disk('public')->delete($m->file_path);
                }
                $m->delete();
            }
        }

        // Process New File Media Uploads
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $mime = $file->getMimeType();
                $type = str_starts_with($mime, 'video/') ? 'video' : 'image';
                $path = $file->store('project_media', 'public');

                $project->media()->create([
                    'type' => $type,
                    'source_type' => 'file',
                    'file_path' => $path,
                ]);
            }
        }

        // Process New External Media URLs
        if ($request->has('media_urls') && is_array($request->input('media_urls'))) {
            foreach ($request->input('media_urls') as $item) {
                if (is_array($item) && !empty($item['url'])) {
                    $project->media()->create([
                        'type' => $item['type'] ?? 'image',
                        'source_type' => 'url',
                        'url' => $item['url'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Toggle the publish status of a project.
     */
    public function togglePublish(Project $project)
    {
        $user = auth()->user();
        $isSuperAdmin = $user?->role === RoleEnums::SuperAdministrator->value;
        $isOwner = $project->user_id === null || $project->user_id === $user?->id;

        if (!$isSuperAdmin && !$isOwner) {
            return back()->with('error', 'Unauthorized. Only the project owner or a Super Administrator can toggle publish status.');
        }

        $project->update([
            'is_published' => !$project->is_published
        ]);

        $status = $project->is_published ? 'published' : 'unpublished';

        // Log action
        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Toggled publish status for project: "' . $project->title . '" to ' . $status
        ]);

        return back()->with('success', "Project is now {$status}.");
    }

    /**
     * Remove the specified resource from storage.
     * ONLY Super Administrator can delete project posts!
     */
    public function destroy(Project $project)
    {
        $user = auth()->user();
        
        if ($user?->role !== RoleEnums::SuperAdministrator->value) {
            return back()->with('error', 'Unauthorized access. Only Super Administrators can delete project posts.');
        }

        if ($project->image_path) {
            Storage::disk('public')->delete($project->image_path);
        }

        $title = $project->title;
        $id = $project->id;

        $project->delete();

        // Log action
        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Deleted project post: "' . $title . '" (ID: ' . $id . ')'
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
}
