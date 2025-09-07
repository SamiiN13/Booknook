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

    public static function getAvailableGenres()
    {
        return [
            'Fiction',
            'Non-Fiction',
            'Mystery',
            'Romance',
            'Science Fiction',
            'Fantasy',
            'Thriller',
            'Horror',
            'Biography',
            'Autobiography',
            'History',
            'Philosophy',
            'Psychology',
            'Self-Help',
            'Business',
            'Technology',
            'Science',
            'Mathematics',
            'Art',
            'Music',
            'Poetry',
            'Drama',
            'Comedy',
            'Adventure',
            'Crime',
            'Young Adult',
            'Children',
            'Educational',
            'Religion',
            'Health',
            'Cooking',
            'Travel',
            'Sports',
            'Politics',
            'Economics',
            'Law',
            'Medicine',
            'Engineering',
            'Architecture',
            'Design',
            'Photography',
            'Gardening',
            'Crafts',
            'Hobbies',
            'Reference',
            'Textbook',
            // more
            'Memoir',
            'Graphic Novel',
            'Comics',
            'Anthology',
            'Short Stories',
            'Dystopian',
            'Post-Apocalyptic',
            'Paranormal',
            'Urban Fantasy',
            'Magical Realism',
            'Western',
            'Humor',
            'Satire',
            'True Crime',
            'Military',
            'War',
            'Nature',
            'Environmental',
            'Parenting',
            'Animals',
            'Anthropology',
            'Sociology',
            'Linguistics',
            'Computer Science',
            'Data Science',
            'AI/ML',
            'Cybersecurity',
            'Programming',
            'Test Prep',
            'Exam Guides',
            'Other'
        ];
    }
}
