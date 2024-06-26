<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'api_keys';

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
