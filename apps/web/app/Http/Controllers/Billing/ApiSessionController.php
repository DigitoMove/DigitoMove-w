<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiSessionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate(['email' => 'required|email', 'password' => 'required|string', 'device_name' => 'required|string|max:100']);
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password) || $user->role !== 'admin') {
            throw ValidationException::withMessages(['email' => 'The supplied credentials are invalid.']);
        }
        return response()->json(['token' => $user->createToken($data['device_name'], ['invoices:manage'])->plainTextToken, 'token_type' => 'Bearer'])
            ->header('Cache-Control', 'no-store');
    }

    public function destroy(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token instanceof \Laravel\Sanctum\PersonalAccessToken) { $token->delete(); }
        return response()->noContent();
    }
}
