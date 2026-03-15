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
    return Inertia::render('Home');
})->name('home');


Route::inertia('/about-us','About')->name('about');
Route::inertia('/services','Services')->name('services');
// Public Portfolio routes
Route::get('/portfolio', [ProjectController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio/{project}', [ProjectController::class, 'show'])->name('portfolio.show');
Route::inertia('/contact','Contact')->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::inertia('/rate','Rate')->name('rate');
Route::inertia('/faq','FAQ')->name('faq');

Route::inertia('/login','Auth/Login')->name('login');
Route::post('/login', [AuthController::class, 'login']);




Route::middleware(['auth', 'admin'])->group(function (){

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Project Routes
    Route::resource('projects', ProjectController::class)->except(['show'])->names('admin.projects');

    //I dont need must of this user route cause staff link will be use to create those route

    Route::get('/logs', [LogController::class, 'index'])->name('log.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Admin Contact Routes
    Route::get('/contacts', [ContactController::class, 'index'])->name('admin.contacts.index');

});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth:web,client')->name('logout');
