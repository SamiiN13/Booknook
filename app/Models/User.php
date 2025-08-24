<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'trust_score',
        'badge',
        'bio',
        'location',
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

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class, 'requester_id');
    }

    public function receivedRequests()
    {
        return $this->hasMany(BookRequest::class, 'owner_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function ratingsGiven()
    {
        return $this->hasMany(UserRating::class, 'rater_id');
    }

    public function ratingsReceived()
    {
        return $this->hasMany(UserRating::class, 'rated_id');
    }

    public function getAverageRatingAttribute()
    {
        $ratings = $this->ratingsReceived();
        if ($ratings->count() === 0) {
            return 0;
        }
        return round($ratings->avg('rating'), 1);
    }

    public function getTotalBooksSharedAttribute()
    {
        return $this->books()->where('status', 'available')->count();
    }

    public function getTotalBooksBorrowedAttribute()
    {
        return $this->bookRequests()->where('status', 'completed')->count();
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function reportsSubmitted()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function getBadgeAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Auto-assign badge based on trust score
        if ($this->trust_score >= 90) {
            return 'Trusted Reader';
        } elseif ($this->trust_score >= 75) {
            return 'Reliable Member';
        } elseif ($this->trust_score >= 60) {
            return 'Active Member';
        } elseif ($this->trust_score >= 40) {
            return 'New Member';
        } else {
            return 'New User';
        }
    }

    public function canAccessRareBooks()
    {
        return $this->trust_score >= 75;
    }

    public function updateTrustScore()
    {
        $score = 50; // Base score

        // Positive factors
        $score += $this->books()->where('status', 'available')->count() * 2; // Sharing books
        $score += $this->bookRequests()->where('status', 'completed')->count() * 3; // Successful borrows
        $score += $this->reviews()->count() * 1; // Writing reviews

        // Negative factors
        $score -= $this->reports()->count() * 10; // Being reported
        $score -= $this->bookRequests()->where('status', 'rejected')->count() * 2; // Rejected requests

        // Ensure score stays within bounds
        $score = max(0, min(100, $score));

        $this->update(['trust_score' => $score]);
    }
}
