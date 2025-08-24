<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'genre',
        'condition',
        'user_id',
        'status',
        'image_path',
        'published_year',
        'pages',
        'language',
        'rarity',
        'min_trust_score',
        'max_loan_duration',
        'due_date'
    ];

    protected $casts = [
        'published_year' => 'integer',
        'pages' => 'integer',
        'min_trust_score' => 'integer',
        'max_loan_duration' => 'integer',
        'due_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requests()
    {
        return $this->hasMany(BookRequest::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function scopeByRarity($query, $rarity)
    {
        return $query->where('rarity', $rarity);
    }

    public function scopeByGenre($query, $genre)
    {
        return $query->where('genre', $genre);
    }

    public function scopeByTrustScore($query, $minScore)
    {
        return $query->where('min_trust_score', '<=', $minScore);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function getAverageRatingAttribute()
    {
        $reviews = $this->reviews()->verified();
        if ($reviews->count() === 0) {
            return 0;
        }
        return round($reviews->avg('rating'), 1);
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->verified()->count();
    }
}
