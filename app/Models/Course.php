<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'thumbnail',
        'preview_video',
        'level',
        'language',
        'price',
        'discount_price',
        'duration_minutes',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'duration_minutes' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(CourseModule::class)->orderBy('sort_order', 'asc');
    }

    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, CourseModule::class, 'course_id', 'module_id')
            ->orderBy('course_modules.sort_order', 'asc')
            ->orderBy('lessons.sort_order', 'asc');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->orderBy('created_at', 'desc');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('instructor', function ($inst) use ($search) {
                        $inst->where('name', 'like', "%{$search}%");
                    });
            });
        });

        $query->when($filters['category'] ?? null, function ($q, $category) {
            if ($category === 'all' || empty($category)) return;
            $q->whereHas('category', fn ($c) => $c->where('slug', $category)->orWhere('id', $category));
        });

        $query->when($filters['level'] ?? null, function ($q, $level) {
            if ($level === 'all' || empty($level)) return;
            $q->where('level', $level);
        });

        $query->when($filters['price'] ?? null, function ($q, $price) {
            if ($price === 'free') {
                $q->where(function ($p) {
                    $p->where('price', 0)->orWhere('discount_price', 0);
                });
            } elseif ($price === 'paid') {
                $q->where(function ($p) {
                    $p->where('price', '>', 0)->where(function ($d) {
                        $d->whereNull('discount_price')->orWhere('discount_price', '>', 0);
                    });
                });
            }
        });

        $query->when($filters['rating'] ?? null, function ($q, $rating) {
            if (empty($rating)) return;
            $q->whereHas('reviews', function ($r) use ($rating) {
                // Course having average rating >= filter
            }, '>=', 1);
        });

        $query->when($filters['sort'] ?? 'newest', function ($q, $sort) {
            switch ($sort) {
                case 'popular':
                    $q->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                    break;
                case 'highest_rated':
                    $q->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                    break;
                case 'price_low':
                    $q->orderByRaw('COALESCE(discount_price, price) asc');
                    break;
                case 'price_high':
                    $q->orderByRaw('COALESCE(discount_price, price) desc');
                    break;
                case 'newest':
                default:
                    $q->orderBy('created_at', 'desc');
                    break;
            }
        });

        return $query;
    }
}
