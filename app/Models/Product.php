<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    public function getRouteKeyName()
    {
        return 'unique_id';
    }

    protected static function booted()
    {
        static::creating(function ($shop) {
            $shop->uuid = uniqid();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
