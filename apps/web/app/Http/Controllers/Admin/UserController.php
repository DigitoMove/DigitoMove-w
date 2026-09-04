<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index() { return view('admin.users.index', ['users' => User::latest()->paginate(15)]); }
    public function create() { return view('admin.users.form', ['user' => new User()]); }
    public function edit(User $user) { return view('admin.users.form', compact('user')); }

    public function store(Request $request)
    {
        User::create($this->validated($request));
        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function update(Request $request, User $user)
    {
        $user->update($this->validated($request, $user));
        return back()->with('success', 'User updated.');
    }

    public function destroy(Request $request, User $user)
    {
        abort_if($request->user()->is($user), 422, 'You cannot delete your own account.');
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user)],
            'phone' => ['nullable', 'string', 'max:40'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'role' => ['required', Rule::in(['admin', 'editor', 'student', 'user'])],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', 'min:8'],
        ]);
        if ($data['password'] ?? null) $data['password'] = Hash::make($data['password']); else unset($data['password']);
        return $data;
    }
}
