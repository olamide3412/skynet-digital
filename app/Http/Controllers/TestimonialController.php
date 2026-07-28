<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Testimonial;
use App\Rules\Turnstile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    /**
     * Public page displaying approved testimonials & submission form.
     */
    public function publicIndex()
    {
        $testimonials = Testimonial::where('is_approved', true)->latest()->get();
        $avgRating = round(Testimonial::where('is_approved', true)->avg('rating') ?? 5, 1);
        $totalApproved = Testimonial::where('is_approved', true)->count();

        return Inertia::render('Testimonials', [
            'testimonials' => $testimonials,
            'stats' => [
                'avgRating' => $avgRating,
                'totalApproved' => $totalApproved,
            ],
        ]);
    }

    /**
     * Handle public visitor submission of a new testimonial with rating and Turnstile.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|max:1000',
            'avatar' => 'nullable|image|max:2048', // 2MB max
            'cf_turnstile_response' => ['required', new Turnstile()],
        ], [
            'cf_turnstile_response.required' => 'Please complete the CAPTCHA verification.',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('testimonials', 'public');
        }

        Testimonial::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company' => $validated['company'],
            'rating' => $validated['rating'],
            'message' => $validated['message'],
            'avatar_path' => $avatarPath,
            'is_approved' => false, // Default to false until admin approves
        ]);

        return back()->with('message', 'Thank you! Your testimonial has been submitted successfully and is pending admin approval.');
    }

    /**
     * Admin Index for reviewing pending & approved testimonials.
     */
    public function adminIndex(Request $request)
    {
        $query = Testimonial::query();

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $testimonials = $query->latest()->paginate(12)->withQueryString();

        $stats = [
            'total' => Testimonial::count(),
            'pending' => Testimonial::where('is_approved', false)->count(),
            'approved' => Testimonial::where('is_approved', true)->count(),
            'avg_rating' => round(Testimonial::avg('rating') ?? 5, 1),
        ];

        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => $testimonials,
            'filters' => $request->only(['status', 'search']),
            'stats' => $stats,
        ]);
    }

    /**
     * Toggle approval status of a testimonial.
     */
    public function toggleApprove(Testimonial $testimonial)
    {
        $testimonial->update([
            'is_approved' => !$testimonial->is_approved
        ]);

        $status = $testimonial->is_approved ? 'approved' : 'revoked/pending';

        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Updated testimonial status for "' . $testimonial->name . '" to ' . $status
        ]);

        return back()->with('message', "Testimonial status set to {$status}.");
    }

    /**
     * Delete a testimonial.
     */
    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->avatar_path) {
            Storage::disk('public')->delete($testimonial->avatar_path);
        }

        $name = $testimonial->name;
        $testimonial->delete();

        Log::create([
            'user_id' => auth()->id(),
            'log' => 'Deleted testimonial submission from "' . $name . '"'
        ]);

        return back()->with('message', 'Testimonial deleted successfully.');
    }
}
