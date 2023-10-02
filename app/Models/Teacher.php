<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    // use HasFactory;
    protected $table = "teachers" ;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_code',
        'contact',
        'age',
        'gender',
        'address',
        'blood_group',
        'status'
    ];
}
