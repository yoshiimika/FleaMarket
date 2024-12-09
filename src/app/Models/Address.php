<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'street',
        'city',
        'zip',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($address) {
            $user = $address->user;
            if ($user && !$user->profile_created) {
                $user->profile_created = true;
                $user->save();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
