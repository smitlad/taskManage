<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    private const STATUSES = [
        'pending',
        'in_progress',
        'completed',
    ];

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['sometimes', 'required', Rule::in(self::STATUSES)],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $query = $request->user()->tasks()->latest();

        if (isset($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        return response()->json(
            $query->paginate($validated['per_page'] ?? 15)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $data['status'] = 'pending';

        $task = $request->user()->tasks()->create($data);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task,
        ], 201);
    }

    public function show(Request $request, string $task): JsonResponse
    {
        $task = $request->user()->tasks()->findOrFail($task);

        return response()->json(['task' => $task]);
    }

    public function update(Request $request, string $task): JsonResponse
    {
        $task = $request->user()->tasks()->findOrFail($task);

        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'due_date' => ['sometimes', 'nullable', 'date_format:Y-m-d'],
            'status' => ['sometimes', 'required', Rule::in(self::STATUSES)],
        ]);

        $task->update($data);

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task->fresh(),
        ]);
    }

    public function destroy(Request $request, string $task): JsonResponse
    {
        $request->user()->tasks()->findOrFail($task)->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
