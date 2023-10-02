<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    // use HasFactory;
    protected $table = 'assessments';

    protected $fillable = [
        'title',
        'description',
        'class_id',
        'teacher_id',
        'subject',
        'test_type',
        'test_id',
        'total_marks',
        'passing_marks',
        'duration'
    ];
}
