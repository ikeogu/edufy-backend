<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'name',
        'location',
        'email',
        'phone',
        'website',
        'logo',
        'description',
        'status',
        'slug',
    ];

    public static function makeSlugFromName($name)
    {
        $slug = Str::slug($name);

        $count = School::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function students(){
        return $this->hasMany(Student::class);
    }

    public function teachers(){
        return $this->hasMany(Teacher::class);
    }

    public function subject(){
        return $this->hasMany(Subject::class);
    }

    public function classes(){
        return $this->hasMany(SchoolClass::class);
    }

    public function user(){
        return $this->belongsTo(User::class,);
    }

    public function categories(){
        return $this->belongsTo(SchoolCategory::class);
    }

    public static function get_school($slug){
        return School::where('slug', $slug)->first();
    }
}
