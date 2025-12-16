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
                'nama_depan' => '',
                'nama_belakang' => '',
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

        // Hapus password dari array untuk profil update
        $profilData = $validated;
        unset($profilData['email'], $profilData['password']);
        $profil->update($profilData);

        // Update user data (email dan password)
        $userData = [
            'email' => $validated['email'],
        ];

        // Hanya update password jika diisi
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Show the profile
     */
    public function show()
    {
        $user = Auth::user();
        $profil = Profil::where('user_id', $user->id)->first();

        if (!$profil) {
            return redirect()->route('profile.edit')->with('info', 'Silakan lengkapi profil Anda terlebih dahulu');
        }

        return view('profile.show', compact('profil'));
    }
}
