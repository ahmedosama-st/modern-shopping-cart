<?php

namespace App\Models;

use App\Models\Traits\HasDefault;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory, HasDefault;

    public $with  = ['country'];

    protected $fillable = [
        'name',
        'address_1',
        'address_2',
        'city',
        'postal_code',
        'country_id',
        'default'
    ];

    protected $casts = [
        'default' => 'bool'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
