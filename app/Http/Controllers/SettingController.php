<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnums;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    private function authorizeSuperAdmin()
    {
        if (auth()->user()?->role !== RoleEnums::SuperAdministrator->value) {
            abort(403, 'Unauthorized access. Only Super Administrators can modify system settings.');
        }
    }

    /**
     * Display the settings view form for Admin.
     * Accessible by Administrators & Super Administrators.
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, [RoleEnums::Administrator->value, RoleEnums::SuperAdministrator->value, RoleEnums::Staff->value])) {
            abort(403, 'Unauthorized access.');
        }

        $settings = Setting::pluck('value', 'key')->all();
        $isSuperAdmin = $user->role === RoleEnums::SuperAdministrator->value;

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings,
            'isSuperAdmin' => $isSuperAdmin,
        ]);
    }

    /**
     * Update system settings in storage.
     * ONLY Super Administrator can save changes!
     */
    public function update(Request $request)
    {
        $this->authorizeSuperAdmin();
        $validated = $request->validate([
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'contact_whatsapp' => 'nullable|string|max:255',
            'contact_address' => 'nullable|string|max:500',
            'contact_hours' => 'nullable|string|max:255',
            'rc_number' => 'nullable|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value, 'contact');
        }

        // Log action
        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Updated system contact & general settings'
        ]);

        return back()->with('success', 'Contact & system settings updated successfully.');
    }
}
