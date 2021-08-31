<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $casts = [
        'default' => 'bool'
    ];

    protected $fillable = [
        'card_type',
        'last_four',
        'provider_id',
        'default'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($paymentMethod) {
            if ($paymentMethod->default) {
                $paymentMethod->user->paymentMethods()->update([
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
}
