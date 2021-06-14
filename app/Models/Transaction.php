<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable =[
        'transaction_id',
        'products',
        'status',
        'amount',
        'payment_method',
    ];

    protected $casts = [
        'products' => 'array',
    ];

    // get all users in transaction table
    public function users(){
        return $this->belongsToMany(User::class);
    }

    // products with transactiond
    public function products(){
        return $this->hasMany(Product::class);
    }
    // products with transactiond
    public function orders(){
        return $this->hasMany(Order::class);
    }

    // products with transactiond
    public function productss(){
        return $this->hasManyThrough(Product::class, Order::class);
    }

    

}
