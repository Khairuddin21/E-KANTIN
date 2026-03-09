<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Gagal login dengan Google. Silakan coba lagi.']);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name'     => $googleUser->getName(),
                'email'    => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(16)),
                'role'     => 'student',
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect($this->redirectByRole($user->role));
    }

    private function redirectByRole(string $role): string
    {
        return match ($role) {
            'super_admin' => '/admin/dashboard',
            'seller'      => '/seller/dashboard',
            'cashier'     => '/cashier/dashboard',
            default       => '/dashboard',
        };
    }
}
