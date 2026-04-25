<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // ============================
        // 2. Pencarian
        // ============================
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // ============================
        // 3. Sorting (Urutkan)
        // terbaru = desc, terlama = asc
        // ============================
        if ($request->sort === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else if ($request->sort === 'terbaru'){
            $query->orderBy('created_at', 'desc'); // default terbaru
        } else if ($request->sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } else if ($request->sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        }

        $users = $query->paginate(10)->withQueryString();
        return view('pages.user.index', compact('users'));
    }

    public function create()
    {
        return view('pages.user.create');
    }

    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.index')->with('error');
    }

    public function show(User $user)
    {

        $totalProject = $user->projectPatrol()->count();

        $totalReport = $user->dailyReport()->count();

        $totalBriefing = $user->safetyBriefing()->count();

        return view('pages.user.show', compact(['user', 'totalProject', 'totalReport', 'totalBriefing']));
    }

    public function edit(User $user)
    {
        return view('pages.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {

        //dd($request);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required'],
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate');
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(8);

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return back()->with('success', 'Password baru: ' . $newPassword);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus');
    }
}
