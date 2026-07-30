<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use Inertia\Inertia;

Route::get('/', function () {
    $teamMembers = \App\Models\TeamMember::where('is_active', true)->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
    return Inertia::render('Home', [
        'teamMembers' => $teamMembers
    ]);
})->name('home');


Route::inertia('/about-us','About')->name('about');
Route::inertia('/services','Services')->name('services');
// Public Our Work routes
Route::get('/our-work', [ProjectController::class, 'ourWork'])->name('our-work');
Route::get('/our-work/{project}', [ProjectController::class, 'show'])->name('our-work.show');
Route::inertia('/contact','Contact')->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Public Testimonials routes
Route::get('/testimonials', [\App\Http\Controllers\TestimonialController::class, 'publicIndex'])->name('testimonials');
Route::post('/testimonials', [\App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');

Route::inertia('/rate','Rate')->name('rate');
Route::inertia('/faq','FAQ')->name('faq');

Route::inertia('/login','Auth/Login')->name('login');
Route::post('/login', [AuthController::class, 'login']);




Route::middleware(['auth', 'admin'])->group(function (){

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Project Routes
    Route::resource('projects', ProjectController::class)->except(['show'])->names('admin.projects');
    Route::patch('projects/{project}/toggle-publish', [ProjectController::class, 'togglePublish'])->name('admin.projects.toggle-publish');

    // Admin Testimonial Routes (Admin & Super Admin)
    Route::get('/admin/testimonials', [\App\Http\Controllers\TestimonialController::class, 'adminIndex'])->name('admin.testimonials.index');
    Route::patch('/admin/testimonials/{testimonial}/toggle-approve', [\App\Http\Controllers\TestimonialController::class, 'toggleApprove'])->name('admin.testimonials.toggle-approve');
    Route::delete('/admin/testimonials/{testimonial}', [\App\Http\Controllers\TestimonialController::class, 'destroy'])->name('admin.testimonials.destroy');

    // Settings Route (Viewable by all admin roles)
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('admin.settings.index');

    // Super Admin Routes (User Management, Team Management & System Settings Update)
    Route::middleware(['super_admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        Route::get('/team-members', [\App\Http\Controllers\TeamMemberController::class, 'index'])->name('admin.team.index');
        Route::post('/team-members', [\App\Http\Controllers\TeamMemberController::class, 'store'])->name('admin.team.store');
        Route::post('/team-members/{teamMember}', [\App\Http\Controllers\TeamMemberController::class, 'update'])->name('admin.team.update');
        Route::patch('/team-members/{teamMember}/toggle-active', [\App\Http\Controllers\TeamMemberController::class, 'toggleActive'])->name('admin.team.toggle-active');
        Route::delete('/team-members/{teamMember}', [\App\Http\Controllers\TeamMemberController::class, 'destroy'])->name('admin.team.destroy');

        Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('admin.settings.update');
    });

    // Admin Contact Routes
    Route::get('/contacts', [ContactController::class, 'index'])->name('admin.contacts.index');

    // Logs Route
    Route::get('/logs', [LogController::class, 'index'])->name('log.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth:web,client')->name('logout');
