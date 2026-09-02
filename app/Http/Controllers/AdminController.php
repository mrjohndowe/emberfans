<?php

namespace App\Http\Controllers;

use App\Models\{CreatorProfile, Payment, Post, Subscription, Tip, User};
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        return view('admin.index', [
            'stats' => [
                'members' => User::count(),
                'creators' => CreatorProfile::count(),
                'posts' => Post::count(),
                'activeSubscriptions' => Subscription::where('status', 'active')->count(),
                'tipsTotal' => Tip::where('status', 'completed')->sum('amount'),
                'paymentsTotal' => Payment::where('status', 'completed')->sum('amount'),
            ],
            'users' => User::latest()->paginate(15),
        ]);
    }

    public function toggleUser(User $user): RedirectResponse
    {
        $this->authorizeAdmin();
        abort_if($user->is(auth()->user()), 422, 'You cannot suspend your own administrator account.');

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', $user->is_active ? 'Account restored.' : 'Account suspended.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === 'admin', 403);
    }
}
