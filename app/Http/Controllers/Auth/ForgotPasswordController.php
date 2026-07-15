<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form lupa password.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with(strtolower($value), '@gmail.com')) {
                        $fail('Hanya email Gmail (@gmail.com) yang diizinkan untuk reset password.');
                    }
                },
            ],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link reset password telah dikirim ke email Gmail Anda. Silakan cek inbox (atau folder Spam).');
        }

        // Email tidak ditemukan di database → pesan generik demi keamanan
        if ($status === Password::INVALID_USER) {
            return back()->with('success', 'Jika email tersebut terdaftar, link reset password telah dikirim ke Gmail Anda.');
        }

        return back()->withErrors(['email' => __($status)])->onlyInput('email');
    }
}
