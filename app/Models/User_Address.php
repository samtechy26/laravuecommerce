<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Address extends Model
{
    protected $fillable = ['address1', 'address2', 'state', 'zipcode', 'city', 'country_code', 'type', 'user_id'];
    use HasFactory;



}
