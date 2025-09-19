<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'user_id',
        'category_id',
        'thumbnail',
        'price',
        'status',
        'level',
        'duration_hours',
        'requirements',
        'what_you_learn',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'requirements' => 'array',
        'what_you_learn' => 'array',
        'published_at' => 'datetime',
    ];

    protected $appends = ['thumbnail_url'];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    // Relationships
    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Accessors
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return Storage::url('course-thumbnails/' . $this->thumbnail);
        }
        
        // Default thumbnail based on category color
        $color = $this->category?->color ?? '6366f1';
        return "https://ui-avatars.com/api/?name=" . urlencode($this->title) . "&background=" . ltrim($color, '#') . "&color=white&size=400";
    }

    public function getFormattedPriceAttribute()
    {
        return $this->price > 0 ? '$' . number_format($this->price, 2) : 'Free';
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'published' => 'bg-green-100 text-green-800',
            'draft' => 'bg-yellow-100 text-yellow-800',
            'archived' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}