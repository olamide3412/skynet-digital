<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnums;
use App\Models\Log;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TeamMemberController extends Controller
{
    private function authorizeSuperAdmin()
    {
        if (auth()->user()?->role !== RoleEnums::SuperAdministrator->value) {
            abort(403, 'Unauthorized access. Only Super Administrators can manage team members.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

        $members = TeamMember::orderBy('order', 'asc')->orderBy('id', 'asc')->get();

        return Inertia::render('Admin/Team/Index', [
            'teamMembers' => $members
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'initials' => 'nullable|string|max:10',
            'gradient' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('team', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? 0;
        $validated['gradient'] = $validated['gradient'] ?? 'from-primary to-indigo-600';

        $member = TeamMember::create($validated);

        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Created team member: "' . $member->name . '" (' . $member->role . ')'
        ]);

        return back()->with('message', 'Team member added successfully.');
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'initials' => 'nullable|string|max:10',
            'gradient' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($teamMember->image_path) {
                Storage::disk('public')->delete($teamMember->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('team', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $teamMember->update($validated);

        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Updated team member details for: "' . $teamMember->name . '"'
        ]);

        return back()->with('message', 'Team member updated successfully.');
    }

    public function toggleActive(TeamMember $teamMember)
    {
        $this->authorizeSuperAdmin();

        $teamMember->update([
            'is_active' => !$teamMember->is_active
        ]);

        $status = $teamMember->is_active ? 'active' : 'hidden';

        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Toggled team member visibility for: "' . $teamMember->name . '" to ' . $status
        ]);

        return back()->with('message', "Team member status set to {$status}.");
    }

    public function destroy(TeamMember $teamMember)
    {
        $this->authorizeSuperAdmin();

        if ($teamMember->image_path) {
            Storage::disk('public')->delete($teamMember->image_path);
        }

        $name = $teamMember->name;
        $teamMember->delete();

        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Deleted team member: "' . $name . '"'
        ]);

        return back()->with('message', 'Team member removed successfully.');
    }
}
