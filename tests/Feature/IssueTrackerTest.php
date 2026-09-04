<?php

use App\Models\Issue;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

/**
 * Workshop starter — multi-tenant issue tracker.
 *
 * Three of the bugs in the codebase are documented as `->todo()` tests
 * below (quota off-by-one, cross-tenant authorization gap, N+1 on the
 * issues index). Fix the bug, then remove `->todo()` so the test runs and
 * passes.
 */

// --- Models ---

it('a tenant has many users', function () {
    $tenant = Tenant::factory()->create();
    User::factory()->count(3)->create(['tenant_id' => $tenant->id]);

    expect($tenant->users()->count())->toBe(3);
});

it('a tenant has many issues', function () {
    $tenant = Tenant::factory()->create();
    Issue::factory()->count(2)->create(['tenant_id' => $tenant->id]);

    expect($tenant->issues()->count())->toBe(2);
});

it('an issue belongs to a tenant', function () {
    $tenant = Tenant::factory()->create();
    $issue = Issue::factory()->create(['tenant_id' => $tenant->id]);

    expect($issue->tenant->id)->toBe($tenant->id);
});

it('an issue belongs to a reporter', function () {
    $user = User::factory()->create();
    $issue = Issue::factory()->create(['reporter_id' => $user->id]);

    expect($issue->reporter->id)->toBe($user->id);
});

it('a user belongs to a tenant', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    expect($user->tenant->id)->toBe($tenant->id);
});

it('the tenant factory creates a tenant', function () {
    $tenant = Tenant::factory()->create(['name' => 'Acme', 'seats_limit' => 4]);

    expect($tenant->name)->toBe('Acme');
    expect($tenant->seats_limit)->toBe(4);
});

it('the issue factory creates an issue', function () {
    $issue = Issue::factory()->create(['title' => 'Log in fails on Safari']);

    expect($issue->title)->toBe('Log in fails on Safari');
    expect($issue->status)->toBeIn(['open', 'in_progress', 'closed']);
});

// --- IssueController ---

it('lists only the authenticated tenant issues', function () {
    $tenantA = Tenant::factory()->create();
    $tenantB = Tenant::factory()->create();
    $userA = User::factory()->create(['tenant_id' => $tenantA->id]);
    Issue::factory()->create(['tenant_id' => $tenantA->id, 'title' => 'Tenant A issue']);
    Issue::factory()->create(['tenant_id' => $tenantB->id, 'title' => 'Tenant B issue']);

    $this->actingAs($userA)
        ->get('/issues')
        ->assertOk()
        ->assertJsonFragment(['title' => 'Tenant A issue'])
        ->assertJsonMissing(['title' => 'Tenant B issue']);
});

it('the issues index requires authentication', function () {
    $this->get('/issues')->assertStatus(401);
});

it('shows an own-tenant issue', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $issue = Issue::factory()->create(['tenant_id' => $tenant->id, 'title' => 'Fix the pipeline']);

    $this->actingAs($user)
        ->get('/issues/'.$issue->id)
        ->assertOk()
        ->assertJsonFragment(['title' => 'Fix the pipeline']);
});

it('the issue show requires authentication', function () {
    $issue = Issue::factory()->create();

    $this->get('/issues/'.$issue->id)->assertStatus(401);
});

// --- LegacyReportController ---

it('the legacy report returns totals', function () {
    $tenant = Tenant::factory()->create();
    Issue::factory()->count(4)->create(['tenant_id' => $tenant->id]);

    $this->getJson('/reports/summary/'.$tenant->id)
        ->assertOk()
        ->assertJson(['total' => 4]);
});

it('the legacy report counts open and closed', function () {
    $tenant = Tenant::factory()->create();
    Issue::factory()->create(['tenant_id' => $tenant->id, 'status' => 'open']);
    Issue::factory()->create(['tenant_id' => $tenant->id, 'status' => 'open']);
    Issue::factory()->create(['tenant_id' => $tenant->id, 'status' => 'closed']);

    $this->getJson('/reports/summary/'.$tenant->id)
        ->assertOk()
        ->assertJson(['open' => 2, 'closed' => 1]);
});

it('the legacy report counts issues created in the last 30 days', function () {
    $tenant = Tenant::factory()->create();
    Issue::factory()->create(['tenant_id' => $tenant->id, 'created_at' => now()]);
    Issue::factory()->create(['tenant_id' => $tenant->id, 'created_at' => now()->subDays(40)]);

    $this->getJson('/reports/summary/'.$tenant->id)
        ->assertOk()
        ->assertJson(['created_last_30_days' => 1]);
});

// --- Seeder ---

it('the database seeder creates sample data', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Tenant::count())->toBeGreaterThanOrEqual(2);
    expect(Issue::count())->toBeGreaterThanOrEqual(5);
});

// --- Known bugs (todo): fix the code, then drop ->todo() ---

it('a tenant cannot seat more users than its seats_limit', function () {
    $tenant = Tenant::factory()->create(['seats_limit' => 2]);
    User::factory()->count(2)->create(['tenant_id' => $tenant->id]);

    // Correct: at the limit, a third seat must be denied.
    // BUG: Tenant::canAddUser() uses `<=`, so this returns true.
    expect($tenant->canAddUser())->toBeFalse();
})->todo();

it('a user cannot view another tenant issue', function () {
    $tenantA = Tenant::factory()->create();
    $tenantB = Tenant::factory()->create();
    $userA = User::factory()->create(['tenant_id' => $tenantA->id]);
    $issueB = Issue::factory()->create(['tenant_id' => $tenantB->id]);

    // Correct: cross-tenant access is denied.
    // BUG: IssueController::show() has no tenant check, so this returns 200.
    $this->actingAs($userA)
        ->get('/issues/'.$issueB->id)
        ->assertStatus(403);
})->todo();

it('listing issues does not trigger an N+1 on the reporter', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    Issue::factory()->count(5)->create(['tenant_id' => $tenant->id, 'reporter_id' => $user->id]);

    $queries = 0;
    DB::listen(function ($query) use (&$queries) {
        if (str_starts_with($query->sql, 'select')) {
            $queries++;
        }
    });

    $this->actingAs($user)->get('/issues')->assertOk();

    // Correct: eager-loaded, so ~2 select queries (issues + reporters).
    // BUG: reporters load one query per issue (N+1).
    expect($queries)->toBeLessThanOrEqual(2);
})->todo();
