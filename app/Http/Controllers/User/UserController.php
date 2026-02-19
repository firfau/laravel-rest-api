<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // GET /api/users
    public function index()
    {
        $users = $this->userService->getAll();

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil diambil.',
            'total'   => $users->count(),
            'data'    => $users,
        ], 200);
    }

    // POST /api/users
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'sometimes|in:admin,user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = $this->userService->create($request->only([
            'name', 'email', 'password', 'role'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat.',
            'data'    => $user,
        ], 201);
    }

    // GET /api/users/{id}
    public function show($id)
    {
        $user = $this->userService->findById($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $user,
        ], 200);
    }

    // PUT /api/users/{id}
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6|confirmed',
            'role'     => 'sometimes|in:admin,user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = $this->userService->update($id, $request->only([
            'name', 'email', 'password', 'role'
        ]));

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate.',
            'data'    => $user,
        ], 200);
    }

    // DELETE /api/users/{id}
    public function destroy($id)
    {
        $result = $this->userService->delete($id, auth()->id());

        if (isset($result['error'])) {
            $statusCode = $result['error'] === 'User tidak ditemukan.' ? 404 : 403;
            return response()->json([
                'success' => false,
                'message' => $result['error'],
            ], $statusCode);
        }

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.',
        ], 200);
    }
}