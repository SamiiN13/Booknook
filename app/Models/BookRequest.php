<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'requester_id',
        'owner_id',
        'status',
        'message',
        'requested_date',
        'approved_date',
        'return_date',
        'due_date',
        'actual_return_date'
    ];

    protected $casts = [
        'requested_date' => 'date',
        'approved_date' => 'date',
        'return_date' => 'date',
        'due_date' => 'date',
        'actual_return_date' => 'date',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
