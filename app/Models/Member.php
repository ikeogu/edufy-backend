<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected static $role = ['student', 'teacher', 'parent', 'school_admin'];

    protected $fillable = [
       'user_id',
       'school_id',
        'role',
    ];
}
