<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    // use HasFactory;
    protected $table = 'tests';

    protected $fillable = [
        'title',
        'description',
        'class_id',
        'teacher_id',
        'subject',
        'test_type',
        'questions',
        'total_marks',
        'passing_marks',
        'duration'
    ];
}
