<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionHistorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'submission_id',
        'approved',
    ];

    public function submission()
    {
        return $this->hasOne(Submission::class, 'id', 'submission_id');
    }
    
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
