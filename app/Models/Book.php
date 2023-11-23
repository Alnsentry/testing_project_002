<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'author',
        'publish_year',
        'stock'
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'book_id', 'id');
    }
}
