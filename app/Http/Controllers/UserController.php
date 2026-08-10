<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->latest();

        if (auth()->user()->hasRole('owner')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'superadmin');
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q
                    ->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $perPage = $request->input('per_page', 10);
        $users = $query->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            return view('dashboard.users._table', compact('users'))->render();
        }

        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'superadmin')->get();
        return view('dashboard.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'is_active' => 'nullable',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->boolean('is_active'),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        if (auth()->user()->hasRole('owner') && $user->hasRole(['superadmin', 'owner']) && $user->id !== auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Tidak memiliki izin.');
        }

        $roles = Role::all();
        if (auth()->user()->hasRole('owner')) {
            $roles = $roles->reject(function ($role) {
                return $role->name === 'superadmin';
            });
        }
        return view('dashboard.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->hasRole('owner') && $user->hasRole(['superadmin', 'owner']) && $user->id !== auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Tidak memiliki izin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|exists:roles,name',
            'is_active' => 'nullable',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->boolean('is_active'),
        ];

        $user->update($data);

        if ($user->id !== auth()->id()) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if (auth()->user()->hasRole('owner') && $user->hasRole(['superadmin', 'owner'])) {
            return redirect()->route('users.index')->with('error', 'Tidak memiliki izin.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
