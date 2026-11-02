<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChallengeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'author_name',
        'author_email',
        'title',
        'category',
        'difficulty',
        'description',
        'hint',
        'hint_file',
        'original_hint_filename',
        'challenge_file',
        'original_challenge_filename',
        'reference_url',
        'suggested_flag',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'web'       => 'Web Exploitation',
            'crypto'    => 'Cryptography',
            'recon'     => 'Reconnaissance',
            'forensics' => 'Digital Forensics',
            'reversing' => 'Reverse Engineering',
            'pwn'       => 'Binary Exploitation',
            default     => 'General Skills',
        };
    }

    public function difficultyLabel(): string
    {
        return match (strtolower($this->difficulty)) {
            'easy'   => 'Mudah',
            'medium' => 'Sedang',
            'hard'   => 'Sulit',
            'insane' => 'Sangat Sulit',
            default  => ucfirst($this->difficulty),
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu Review',
            'reviewing' => 'Sedang Ditinjau',
            'approved'  => 'Disetujui',
            'rejected'  => 'Memerlukan Revisi',
            default     => ucfirst($this->status),
        };
    }

    public function hintFileUrl(): ?string
    {
        return $this->hint_file ? asset('storage/' . $this->hint_file) : null;
    }

    public function challengeFileUrl(): ?string
    {
        return $this->challenge_file ? asset('storage/' . $this->challenge_file) : null;
    }
}

