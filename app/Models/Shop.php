<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    #TODO category_id
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
