<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => ['required', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'phone'    => 'nullable|string|max:20',
            'class'    => 'nullable|string|max:50',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'current_password'  => 'nullable|required_with:new_password|string',
            'new_password'      => 'nullable|string|min:8|confirmed',
        ]);

        // Verify current password if changing password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.'])->withInput();
            }
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->class = $validated['class'] ?? $user->class;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // Handle password change
        if ($request->filled('new_password')) {
            $user->password = $request->new_password;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
