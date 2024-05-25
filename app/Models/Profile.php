<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['user_id', 'profile_image', 'gender', 'birthday','phone'];
    use HasFactory;

    public function addresses () {
        return $this->hasMany(User_Address::class);
    }

    public function user () {
        return $this->belongsTo(User::class);
    }
}
