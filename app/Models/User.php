<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public static  $roles = ['admin', 'student', 'teacher', 'parent','school_admin'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

  /*   public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
 */
    public function memberships()
    {
        return $this->hasMany(Student::class, 'user_id')->with('school');
    }

    public function personal_details()
    {
        return $this->hasOne(UserPersonalDetails::class, 'user_id');
    }

    public function belongs_to_school($school)
    {
        return $this->memberships()->where('school_id', $school->id)->count();
    }

    public function admin_of_school($school)
    {
        return $this->admin_membership()->
            where('school_id', $school->id)->
            count();
    }

    public function admin_membership()
    {
        return $this->hasOne(SchoolAdmin::class,'user_id')->with('school');
    }
}
