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

        $issues = Issue::where('tenant_id', $user->tenant_id)->get();

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

        return response()->json([
            'id' => $issue->id,
            'title' => $issue->title,
            'status' => $issue->status,
            'description' => $issue->description,
            'reporter' => $issue->reporter?->name,
        ]);
    }

    /**
     * Close several issues at once. Issues that are already closed are skipped.
     */
    public function bulkClose(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        /** @var list<int> $ids */
        $ids = $request->input('ids', []);

        // Make sure the user is allowed to close these issues.
        $first = Issue::find($ids[0] ?? 0);
        if ($first === null || $first->tenant_id !== $user->tenant_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $closed = 0;
        foreach ($ids as $id) {
            $issue = Issue::find($id);
            if ($issue === null || $issue->status === 'closed') {
                continue;
            }

            $issue->status = 'closed';
            $issue->save();
            $closed++;
        }

        return response()->json(['closed' => $closed]);
    }
}
