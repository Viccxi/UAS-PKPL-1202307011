<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{
    /**
     * Daftar tugas.
     * - User: hanya tugasnya sendiri.
     * - Admin: semua tugas.
     * Mendukung filter: status, priority, search (title).
     *
     * GET /api/tasks
     */
    public function index(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user  = $request->user();
        $query = Task::with('user');

        // Batasi scope berdasarkan role
        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Pencarian berdasarkan title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $tasks = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar tugas berhasil diambil.',
            'data'    => $tasks,
        ]);
    }

    /**
     * Simpan tugas baru.
     * POST /api/tasks
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $task = Task::create([
            'user_id'     => $user->id,
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority,
            'status'      => $request->status,
            'due_date'    => $request->due_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil ditambahkan.',
            'data'    => $task->load('user'),
        ], 201);
    }

    /**
     * Detail tugas berdasarkan ID.
     * - User hanya bisa melihat miliknya.
     * - Admin bisa melihat semua.
     *
     * GET /api/tasks/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $task = Task::with('user')->find($id);

        if (! $task) {
            return response()->json([
                'success' => false,
                'message' => 'Data tugas tidak ditemukan.',
            ], 404);
        }

        $this->authorize('view', $task);

        return response()->json([
            'success' => true,
            'message' => 'Detail tugas berhasil diambil.',
            'data'    => $task,
        ]);
    }

    /**
     * Update tugas berdasarkan ID.
     * - User hanya bisa mengubah miliknya.
     *
     * PUT/PATCH /api/tasks/{id}
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $task = Task::find($id);

        if (! $task) {
            return response()->json([
                'success' => false,
                'message' => 'Data tugas tidak ditemukan.',
            ], 404);
        }

        $this->authorize('update', $task);

        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui.',
            'data'    => $task->load('user'),
        ]);
    }

    /**
     * Hapus tugas berdasarkan ID.
     * - User hanya bisa menghapus miliknya.
     * - Admin bisa menghapus semua.
     *
     * DELETE /api/tasks/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $task = Task::find($id);

        if (! $task) {
            return response()->json([
                'success' => false,
                'message' => 'Data tugas tidak ditemukan.',
            ], 404);
        }

        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus.',
            'data'    => null,
        ]);
    }

    /**
     * Tandai tugas sebagai selesai (completed).
     * PATCH /api/tasks/{id}/complete
     */
    public function complete(Request $request, int $id): JsonResponse
    {
        $task = Task::find($id);

        if (! $task) {
            return response()->json([
                'success' => false,
                'message' => 'Data tugas tidak ditemukan.',
            ], 404);
        }

        $this->authorize('complete', $task);

        $task->update([
            'status'       => 'completed',
            'completed_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil ditandai sebagai selesai.',
            'data'    => $task->load('user'),
        ]);
    }
}
