<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use App\Enums\RoleEnums;
use App\Enums\StatusEnums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(12)->withQueryString();

        // Stats calculation
        $stats = [
            'total' => User::count(),
            'active' => User::where('status', StatusEnums::Enable->value)->count(),
            'disabled' => User::where('status', StatusEnums::Disable->value)->count(),
            'admins' => User::whereIn('role', [RoleEnums::Administrator->value, RoleEnums::SuperAdministrator->value])->count(),
        ];

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'status']),
            'stats' => $stats,
            'roles' => array_map(fn($r) => $r->value, RoleEnums::cases()),
            'statuses' => array_map(fn($s) => $s->value, StatusEnums::cases()),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(array_map(fn($r) => $r->value, RoleEnums::cases()))],
            'status' => ['required', Rule::in(array_map(fn($s) => $s->value, StatusEnums::cases()))],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Created user account: "' . $user->name . '" (' . $user->email . ')'
        ]);

        return back()->with('message', 'User account created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(array_map(fn($r) => $r->value, RoleEnums::cases()))],
            'status' => ['required', Rule::in(array_map(fn($s) => $s->value, StatusEnums::cases()))],
        ]);

        $user->update($validated);

        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Updated user account: "' . $user->name . '" (' . $user->email . ')'
        ]);

        return back()->with('message', 'User account updated successfully.');
    }

    public function toggleStatus(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot disable your own active account.']);
        }

        $newStatus = $user->status === StatusEnums::Enable->value 
            ? StatusEnums::Disable->value 
            : StatusEnums::Enable->value;

        $user->update(['status' => $newStatus]);

        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Toggled status for user: "' . $user->name . '" (' . $user->email . ') to ' . $newStatus
        ]);

        return back()->with('message', "User status updated to {$newStatus}.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Reset password for user: "' . $user->name . '" (' . $user->email . ')'
        ]);

        return back()->with('message', "Password for {$user->name} reset successfully.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $name = $user->name;
        $email = $user->email;

        $user->delete();

        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Deleted user account: "' . $name . '" (' . $email . ')'
        ]);

        return back()->with('message', 'User account deleted successfully.');
    }
}
