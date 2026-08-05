<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
        // 1. Ambil semua data validasi KECUALI avatar
        $validatedData = $request->validated();
        
        $user = $request->user();

        // 2. Isi data name dan email (tanpa avatar)
        $user->fill(collect($validatedData)->except('avatar')->toArray());

        // 3. Jika email berubah, reset verifikasi
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 4. Handle Avatar Upload secara terpisah
        if ($request->hasFile('avatar')) {
            $dir = storage_path('app/public/avatars');
            if (!file_exists($dir)) {
                @mkdir($dir, 0755, true);
            }

            // Hapus foto lama jika ada
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Simpan foto baru ke folder 'storage/app/public/avatars'
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // Simpan path yang benar ke database
            $user->avatar = $path;

            // Dual-store copy to public_path for cPanel compatibility
            try {
                $publicCopy = public_path('storage/' . $path);
                @mkdir(dirname($publicCopy), 0755, true);
                @copy(storage_path('app/public/' . $path), $publicCopy);
            } catch (\Throwable $e) {}
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

        // Hapus foto avatar juga saat akun dihapus
        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
