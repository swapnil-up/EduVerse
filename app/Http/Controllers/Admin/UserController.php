<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('admin-access');

        $users = User::latest()->paginate(10);

        return inertia('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        Gate::authorize('admin-access');

        return inertia('Admin/Users/Create', [
            'storeRoute' => route('admin.users.store'),
            'indexRoute' => route('admin.users.index'),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('admin-access');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,instructor,student',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        User::create($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        Gate::authorize('admin-access');

        return inertia('Admin/Users/Edit', [
            'user' => $user,
            'updateRoute' => route('admin.users.update', $user),
            'indexRoute' => route('admin.users.index'),
        ]);
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('admin-access');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,instructor,student',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        Gate::authorize('admin-access');

        if ($user->id === auth()->id()) {
            return redirect()->back()->withErrors('You cannot delete yourself!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User deleted successfully!');
    }
}