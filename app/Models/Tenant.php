<?php

namespace App\Models;

use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    /** @use HasFactory<TenantFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'seats_limit',
    ];

    protected function casts(): array
    {
        return [
            'seats_limit' => 'integer',
        ];
    }

    /** @return HasMany<User, $this> */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /** @return HasMany<Issue, $this> */
    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }

    /**
     * Whether another seat (billed user) may be added to this tenant.
     *
     * BUG (off-by-one): this uses `<=` so it allows `seats_limit + 1`
     * users before reporting full. It should be `< seats_limit`.
     */
    public function canAddUser(): bool
    {
        return $this->users()->count() <= $this->seats_limit;
    }
}
