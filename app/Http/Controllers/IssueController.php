<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * List the authenticated user's tenant issues.
     *
     * BUG (N+1): reporters are loaded one query per issue instead of
     * being eager-loaded. Accessing `$issue->reporter->name` here fires a
     * query for every row. Fix with `with('reporter')` on the query.
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
     *
     * BUG (authorization gap): any authenticated user can view any issue,
     * even one belonging to another tenant. There is no check that
     * `$issue->tenant_id` matches the authenticated user's `tenant_id`.
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
}
