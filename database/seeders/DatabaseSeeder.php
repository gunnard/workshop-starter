<?php

namespace Database\Seeders;

use App\Models\Issue;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::create([
            'name' => 'Acme Healthcare',
            'seats_limit' => 3,
        ]);

        $alice = User::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Alice Admin',
            'email' => 'alice@acme.test',
        ]);

        User::factory()->count(2)->create([
            'tenant_id' => $tenant->id,
        ]);

        Issue::factory()->count(6)->create([
            'tenant_id' => $tenant->id,
            'reporter_id' => $alice->id,
        ]);

        // A second tenant to demonstrate cross-tenant leakage.
        $other = Tenant::create(['name' => 'Beta Labs', 'seats_limit' => 2]);
        User::factory()->create([
            'tenant_id' => $other->id,
            'email' => 'bob@beta.test',
        ]);
        Issue::factory()->count(3)->create([
            'tenant_id' => $other->id,
            'reporter_id' => $other->users()->first()->id,
        ]);
    }
}
