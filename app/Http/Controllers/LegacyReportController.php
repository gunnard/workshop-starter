<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * A deliberately "legacy" report controller.
 *
 * It is the target of the workshop's refactoring exercise: it has an N+1
 * query (each issue loads its reporter separately), duplicated date logic,
 * no tenant-scoping, and no tests. The exercise is to characterize it and
 * extract one concern at a time without changing behavior.
 */
class LegacyReportController extends Controller
{
    public function summary(Request $request, Tenant $tenant): JsonResponse
    {
        $issues = $tenant->issues()->get();

        $open = 0;
        $closed = 0;
        $byReporter = [];

        foreach ($issues as $issue) {
            if ($issue->status === 'open') {
                $open++;
            } else {
                $closed++;
            }

            // N+1: one query per issue to resolve the reporter.
            $reporter = $issue->reporter->name ?? 'unknown';
            $byReporter[$reporter] = ($byReporter[$reporter] ?? 0) + 1;
        }

        // Duplicated date handling: the same "30 days ago" threshold is
        // computed inline in two places below.
        $since = now()->subDays(30);
        $recent = $issues->filter(fn ($issue) => $issue->created_at !== null && $issue->created_at->greaterThanOrEqualTo($since))->count();

        return response()->json([
            'tenant' => $tenant->name,
            'total' => $issues->count(),
            'open' => $open,
            'closed' => $closed,
            'by_reporter' => $byReporter,
            'created_last_30_days' => $recent,
        ]);
    }
}
