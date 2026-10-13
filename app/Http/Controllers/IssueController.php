<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * List the authenticated user's tenant issues.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user === null || $user->tenant_id === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $issues = Issue::with('reporter')->where('tenant_id', $user->tenant_id)->get();

        return response()->json([
            'issues' => $issues->map(fn (Issue $issue) => [
                'id' => $issue->id,
                'title' => $issue->title,
                'status' => $issue->status,
                'reporter' => $issue->reporter?->name,
            ]),
        ]);
    }

    /**
     * Show a single issue.
     */
    public function show(Request $request, Issue $issue): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($issue->tenant_id !== $user->tenant_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return response()->json([
            'id' => $issue->id,
            'title' => $issue->title,
            'status' => $issue->status,
            'description' => $issue->description,
            'reporter' => $issue->reporter?->name,
        ]);
    }
}
