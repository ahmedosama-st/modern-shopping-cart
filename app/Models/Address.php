<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            if ($address->default) {
                $address->user->addresses()->update([
                    'default' => false
                ]);
            }
        });
    }

    public function setDefaultAttribute($value)
    {
        $this->attributes['default'] = ($value === 'true' ? true : false);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
