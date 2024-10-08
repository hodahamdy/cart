<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;
    protected $table='carts';
    protected $fillable =
    [
        'product_id',
        'color_id',
        'size_id',
        'user_id',
        'quantity',
        'price',
        'features',
        'sub_total'
    ];
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function color(){
        return $this->belongsTo(ProductColor::class);
    }
    public function size(){
        return $this->belongsTo(ProductSize::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
