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
        'language'
    ];

    protected $casts = [
        'published_year' => 'integer',
        'pages' => 'integer',
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
}
