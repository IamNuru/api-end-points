<?php

namespace App\Models;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable =[
        'transaction_id',
        'product_id',
        'qty'
    ];

    public function products(){
        return $this->hasMany(Product::class, 'id', 'product_id');
    }

    public function users(){
        return $this->hasMany(User::class, 'id', 'user_id');
    }

    public function transactions(){
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }
}
