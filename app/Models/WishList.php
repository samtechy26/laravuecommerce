<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    protected $fillable = ['user_id', 'product_id'];
    use HasFactory;

    public function user () {
        $this->belongsTo(User::class);
    }

    public function product () {
        $this->belongsTo(Product::class);
    }
}
