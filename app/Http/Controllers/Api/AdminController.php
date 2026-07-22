<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    /**
     * Melihat semua pengguna yang terdaftar.
     * GET /api/admin/users
     */
    public function users(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'role', 'created_at')
            ->withCount('tasks')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua pengguna berhasil diambil.',
            'data'    => $users,
        ]);
    }

    /**
     * Melihat seluruh tugas beserta data pemiliknya.
     * GET /api/admin/tasks
     */
    public function tasks(): JsonResponse
    {
        $tasks = Task::with('user:id,name,email,role')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Seluruh tugas berhasil diambil.',
            'data'    => $tasks,
        ]);
    }
}
