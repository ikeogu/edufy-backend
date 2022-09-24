<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'start_year',
        'pssword',
        'teacher_category',
        'user_id',
        'passport',
        'dob',
        'gender',
    ];

    public function school(){
        return $this->belongsTo(School::class);
    }
}
