<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'passing_score',
        'time_limit_minutes',
    ];

    protected function casts(): array
    {
        return [
            'passing_score' => 'integer',
            'time_limit_minutes' => 'integer',
        ];
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order', 'asc');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class)->orderBy('created_at', 'desc');
    }

    public function bestAttemptForUser(int $userId): ?QuizAttempt
    {
        return $this->attempts()->where('user_id', $userId)->orderBy('score', 'desc')->first();
    }
}
