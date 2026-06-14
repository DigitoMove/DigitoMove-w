<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('admin.profile', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user)],
            'phone' => ['nullable', 'string', 'max:40'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);
        if ($data['password'] ?? null) $data['password'] = Hash::make($data['password']); else unset($data['password']);
        $user->update($data);

        return back()->with('success', 'Profile updated.');
    }
}
