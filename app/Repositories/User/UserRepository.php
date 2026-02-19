<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * Ambil semua user
     */
    public function getAll()
    {
        return User::all();
    }

    /**
     * Cari user by ID
     */
    public function findById($id)
    {
        return User::find($id);
    }

    /**
     * Buat user baru
     */
    public function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'] ?? 'user',
        ]);
    }

    /**
     * Update user
     */
    public function update(User $user, array $data)
    {
        if (isset($data['name']))     $user->name     = $data['name'];
        if (isset($data['email']))    $user->email    = $data['email'];
        if (isset($data['role']))     $user->role     = $data['role'];
        if (isset($data['password'])) $user->password = Hash::make($data['password']);

        $user->save();

        return $user;
    }

    /**
     * Hapus user
     */
    public function delete(User $user)
    {
        $user->tokens()->delete();
        $user->delete();
    }
}