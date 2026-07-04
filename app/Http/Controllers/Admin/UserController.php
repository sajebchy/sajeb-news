<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with('roles')->withCount('newsArticles')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $users = $query->paginate(20)->withQueryString();
        $roles  = Role::all();

        $totalUsers  = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $todayUsers  = User::whereDate('created_at', today())->count();

        return view('admin.users.index', compact('users', 'roles', 'totalUsers', 'activeUsers', 'todayUsers'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();

        return view('admin.users.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created user in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'email_verified_at' => now(),
        ]);

        // Convert role IDs to role names
        $roleNames = Role::whereIn('id', $validated['roles'])->pluck('name')->toArray();
        
        // Assign roles by name
        $user->syncRoles($roleNames);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'সুপার অ্যাডমিন প্রোফাইল সম্পাদনা করার অনুমতি নেই!');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        if (auth()->id() !== $user->id) {
            if (!empty($validated['roles'])) {
                $roleNames = Role::whereIn('id', $validated['roles'])->pluck('name')->toArray();
            } else {
                $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
                $roleNames = [$userRole->name];
            }
            $user->syncRoles($roleNames);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'ব্যবহারকারী সফলভাবে আপডেট হয়েছে!');
    }

    /**
     * Delete the specified user.
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'আপনি নিজের অ্যাকাউন্ট মুছতে পারবেন না!');
        }

        if ($user->hasRole('super-admin') && !auth()->user()->hasRole('super-admin')) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'সুপার অ্যাডমিন অ্যাকাউন্ট মুছে ফেলার অনুমতি নেই!');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Update the specified user's password.
     */
    public function changePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        // Update password
        $user->update([
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Password updated successfully!');
    }
}
