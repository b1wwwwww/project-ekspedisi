<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $pengguna = $request->user();

        $perubahan = [
            'password' => Hash::make($validated['password']),
        ];

        // Jika yang mengganti password adalah Kurir,
        // tandai bahwa kewajiban mengganti password sudah selesai.
        if ($pengguna->role === 'kurir') {
            $perubahan['wajib_ganti_password'] = false;
        }

        $pengguna->update($perubahan);

        return back()->with('status', 'password-updated');
    }
}
