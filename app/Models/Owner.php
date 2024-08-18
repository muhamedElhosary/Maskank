<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class Owner extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'username',
        'owner_name',
        'email',
        'phone',
        'password',
        'national_id',
        'status',             //default (0)   //اذا تم قبول بوست للمالك تتحول الي (1)
        'email_verified_at',
        'photo',


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
        'password' => 'hashed',
    ];
}
