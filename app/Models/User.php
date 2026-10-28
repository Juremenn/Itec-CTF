<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'registration_ip',
        'device_fingerprint',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function solves(): HasMany
    {
        return $this->hasMany(Solve::class);
    }

    public function solvedChallenges(): BelongsToMany
    {
        return $this->belongsToMany(Challenge::class, 'solves')
            ->withPivot('mode', 'solved_at')
            ->withTimestamps();
    }

    public function hasSolved(Challenge|int $challenge): bool
    {
        $id = $challenge instanceof Challenge ? $challenge->id : $challenge;
        return $this->solves()->where('challenge_id', $id)->exists();
    }
}
