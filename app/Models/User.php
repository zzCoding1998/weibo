<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gravatar()
    {
        return '/images/gravatar/default.jpeg';
    }

    public function statuses()
    {
        return $this->hasMany(Status::class,'user_id','id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class,'followers','user_id','follower_id','id','id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class,'followers','follower_id','user_id','id','id');
    }

    public function follow($user_ids)
    {
        $user_ids = Arr::wrap($user_ids);

        $this->followings()->sync($user_ids,false);
    }

    public function unfollow($user_ids)
    {
        $user_ids = Arr::wrap($user_ids);

        $this->followings()->detach($user_ids);
    }

    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
