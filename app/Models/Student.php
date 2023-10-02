<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // use HasFactory;
    protected $table = 'students';

    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'gender',
        'address',
        'father_name',
        'mother_name',
        'guardian_number',
        'email',
        'password',
        'phone_code',
        'contact',
        'user_type',
        'blood_group',
        'status',
    ];
    
}
