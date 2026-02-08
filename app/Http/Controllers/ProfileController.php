<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form
     */
    public function edit()
    {
        $user = Auth::user();
        $profil = Profil::where('user_id', $user->id)->first();

        if (!$profil) {
            // Create profil if doesn't exist
            $profil = Profil::create([
                'user_id' => $user->id,
                'nama_depan' => explode(' ', $user->name)[0] ?? '',
                'nama_belakang' => explode(' ', $user->name)[1] ?? '',
                'telepon' => '',
            ]);
        }

        return view('profile.edit', compact('profil', 'user'));
    }

    /**
     * Update the profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'nama_depan' => 'required|string|max:255',
            'nama_belakang' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update profil data
        $profil = Profil::where('user_id', $user->id)->first();

        if (!$profil) {
            $profil = new Profil();
            $profil->user_id = $user->id;
        }

        // Update profil fields
        $profil->nama_depan = $validated['nama_depan'];
        $profil->nama_belakang = $validated['nama_belakang'];
        $profil->telepon = $validated['telepon'] ?? null;
        $profil->save();

        // Update user name (gabungan nama depan + belakang)
        $user->name = trim($validated['nama_depan'] . ' ' . $validated['nama_belakang']);
        $user->email = $validated['email'];

        // Hanya update password jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Show the profile
     */
    public function show()
    {
        $user = Auth::user();
        $profil = Profil::where('user_id', $user->id)->first();

        if (!$profil) {
            // Buat profil default dari nama user
            $profil = Profil::create([
                'user_id' => $user->id,
                'nama_depan' => explode(' ', $user->name)[0] ?? $user->name,
                'nama_belakang' => explode(' ', $user->name)[1] ?? '',
                'telepon' => '',
            ]);
        }

        return view('profile.show', compact('profil'));
    }
}
