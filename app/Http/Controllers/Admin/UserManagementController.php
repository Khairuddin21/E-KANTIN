<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['student', 'teacher']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20);

        $stats = [
            'total'    => User::whereIn('role', ['student', 'teacher'])->count(),
            'student'  => User::where('role', 'student')->count(),
            'teacher'  => User::where('role', 'teacher')->count(),
            'active'   => User::whereIn('role', ['student', 'teacher'])->where('is_active', true)->count(),
            'inactive' => User::whereIn('role', ['student', 'teacher'])->where('is_active', false)->count(),
        ];

        return view('admin.users', compact('users', 'stats'));
    }

    public function toggleStatus(User $user)
    {
        if (!in_array($user->role, ['student', 'teacher'])) {
            return back()->with('error', 'Hanya bisa mengubah status siswa/guru.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Pengguna {$user->name} berhasil {$status}.");
    }

    public function destroy(User $user)
    {
        if (!in_array($user->role, ['student', 'teacher'])) {
            return back()->with('error', 'Hanya bisa menghapus akun siswa/guru.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Akun {$name} berhasil dihapus.");
    }
}
