<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'difficulty',
        'points',
        'flag',
        'description',
        'hint',
        'remediation',
        'mode_target',
        'is_active',
    ];

    protected $casts = [
        'points' => 'integer',
        'is_active' => 'boolean',
    ];

    public function solves(): HasMany
    {
        return $this->hasMany(Solve::class);
    }

    public function solvers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'solves')
            ->withPivot('mode', 'solved_at')
            ->withTimestamps();
    }

    public function isSolvedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->solves()->where('user_id', $user->id)->exists();
    }

    public function solveFor(?User $user): ?Solve
    {
        if (!$user) {
            return null;
        }

        return $this->solves()->where('user_id', $user->id)->first();
    }
}

