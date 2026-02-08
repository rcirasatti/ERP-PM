<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::with('profil')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Statistics
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $managerCount = User::where('role', 'manager')->count();

        return view('user.index', compact('users', 'totalUsers', 'adminCount', 'managerCount'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager',
            'nama_depan' => 'required|string|max:255',
            'nama_belakang' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Create profil
        Profil::create([
            'user_id' => $user->id,
            'nama_depan' => $validated['nama_depan'],
            'nama_belakang' => $validated['nama_belakang'],
            'telepon' => $validated['telepon'] ?? '',
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load('profil');
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $user->load('profil');
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,manager',
            'nama_depan' => 'required|string|max:255',
            'nama_belakang' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
        ]);

        // Update user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        // Update or create profil
        $profil = $user->profil;
        if (!$profil) {
            $profil = new Profil();
            $profil->user_id = $user->id;
        }
        
        $profil->nama_depan = $validated['nama_depan'];
        $profil->nama_belakang = $validated['nama_belakang'];
        $profil->telepon = $validated['telepon'] ?? '';
        $profil->save();

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        // Delete profil first (cascade should handle this, but just to be safe)
        if ($user->profil) {
            $user->profil->delete();
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }
}
