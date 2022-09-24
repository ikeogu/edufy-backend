<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'reg_no',
        'dob',
        'gender',
        'p_email',
        'contact',
        'address',
        'passport',
        'pssword',
        'user_id'
    ];


    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
