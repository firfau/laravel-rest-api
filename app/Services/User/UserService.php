<?php

namespace App\Services\User;

use App\Repositories\User\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll()
    {
        return $this->userRepository->getAll();
    }

    public function findById($id)
    {
        return $this->userRepository->findById($id);
    }

    public function create(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->userRepository->findById($id);

        if (!$user) return null;

        return $this->userRepository->update($user, $data);
    }

    public function delete($id, $currentUserId): array
    {
        // Cek tidak boleh hapus diri sendiri
        if ($id == $currentUserId) {
            return ['error' => 'Tidak bisa menghapus akun Anda sendiri.'];
        }

        $user = $this->userRepository->findById($id);

        if (!$user) return ['error' => 'User tidak ditemukan.'];

        $this->userRepository->delete($user);

        return ['success' => true];
    }
}