<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    use HasFactory;

    public $fillable = [
        'good_count_from',
        'good_count_to',
        'percent',
        'price',
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
