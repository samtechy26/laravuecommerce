<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['total_price', 'status', 'session_id', 'user__address_id', 'created_by', 'updated_by'];
    use HasFactory;


    function order_items() {
        return $this->hasMany(OrderItem::class);
    }

   function user() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
