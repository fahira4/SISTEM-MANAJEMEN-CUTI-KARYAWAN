<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // ✅ JANGAN LUPA IMPORT INI
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Update data dasar (name, email)
        $user->fill($request->validated());

        // Update data tambahan (Phone & Address)
        // Pastikan field ini ada di $fillable pada Model User
        $user->phone_number = $request->input('phone_number');
        $user->address = $request->input('address');

        // ✅ LOGIKA UPLOAD FOTO PROFIL
        if ($request->hasFile('profile_photo')) {
            // 1. Validasi sederhana (opsional, sebaiknya di ProfileUpdateRequest)
            $request->validate([
                'profile_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            ]);

            // 2. Hapus foto lama jika ada (agar tidak menumpuk sampah)
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // 3. Simpan foto baru
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        // Reset verifikasi email jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}