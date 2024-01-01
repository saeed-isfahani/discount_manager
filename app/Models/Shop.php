<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function province()
    {
        return $this->belongsTo(ProvinceCity::class);
    }

    public function city()
    {
        return $this->belongsTo(ProvinceCity::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function booted()
    {
        static::creating(function ($shop) {
            $shop->uuid = uniqid();
        });
    }
}
