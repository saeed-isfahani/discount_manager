<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvinceCity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'unique_id'
    ];

    protected $guarded = [
        'id',
    ];

    public function getRouteKeyName()
    {
        return 'unique_id';
    }
}
