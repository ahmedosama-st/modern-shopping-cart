<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'code', 'name'
    ];

    public function shippingMethods()
    {
        return $this->belongsToMany(ShippingMethod::class);
    }
}
