<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;


class Users extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [ 'name', 'email', 'password', ];

}