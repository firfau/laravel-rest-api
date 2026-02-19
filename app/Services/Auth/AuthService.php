<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Proses login, return token jika berhasil
     */
    public function login(string $email, string $password): ?array
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return null;
        }

        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Proses logout, hapus token aktif
     */
    public function logout($request): void
    {
        $request->user()->currentAccessToken()->delete();
    }
}