<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index() {
        $users = User::paginate(10);
        return view('pages.user.index', compact('users'));
    }

    public function create() {
        return view('pages.user.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('daily-safety-patrol.index');
    }

    public function show(User $user) {

        $totalProject = $user->projectPatrol()->count();

        $totalReport = $user->dailyReport()->count();

        $totalBriefing = $user->safetyBriefing()->count();

        return view('pages.user.show', compact(['user', 'totalProject', 'totalReport', 'totalBriefing']));
    }
}
