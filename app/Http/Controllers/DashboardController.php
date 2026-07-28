<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnums;
use App\Models\ContactMessage;
use App\Models\Log;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $adminsCount = User::where('role', RoleEnums::Administrator->value)->count();
        $superAdminsCount = User::where('role', RoleEnums::SuperAdministrator->value)->count();

        $projectsCount = Project::count();
        $publishedProjectsCount = Project::where('is_published', true)->count();
        $draftProjectsCount = Project::where('is_published', false)->count();

        $messagesCount = ContactMessage::count();
        $logsCount = Log::count();

        $recentProjects = Project::latest()->limit(5)->get();
        $recentMessages = ContactMessage::latest()->limit(5)->get();

        return inertia('Auth/Dashboard', [
            'counts' => [
                'users' => $usersCount,
                'admins' => $adminsCount,
                'superAdmins' => $superAdminsCount,
                'projects' => $projectsCount,
                'publishedProjects' => $publishedProjectsCount,
                'draftProjects' => $draftProjectsCount,
                'messages' => $messagesCount,
                'logs' => $logsCount,
            ],
            'recentProjects' => $recentProjects,
            'recentMessages' => $recentMessages,
        ]);
    }
}
