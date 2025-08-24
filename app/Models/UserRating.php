<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'rater_id',
        'rated_id',
        'book_request_id',
        'rating',
        'comment',
        'type'
    ];

    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    public function rated()
    {
        return $this->belongsTo(User::class, 'rated_id');
    }

    public function bookRequest()
    {
        return $this->belongsTo(BookRequest::class);
    }

    public function scopeForBorrower($query)
    {
        return $query->where('type', 'borrower');
    }

    public function scopeForLender($query)
    {
        return $query->where('type', 'lender');
    }
}
