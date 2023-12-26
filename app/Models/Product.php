<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'description',
        'image',
        'price',
        'category_id',
        'expire_at',
        'expire_soon',
        'shop_id'
    ];

    public $guarded = ['id'];

    public function getRouteKeyName()
    {
        return 'unique_id';
    }

    protected static function booted()
    {
        static::creating(function ($shop) {
            $shop->unique_id = uniqid();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product_discount()
    {
        return $this->hasMany(ProductDiscount::class, 'product_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
