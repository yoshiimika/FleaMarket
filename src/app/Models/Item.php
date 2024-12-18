<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brand_id',
        'name',
        'color',
        'description',
        'price',
        'img_url',
        'condition',
        'is_sold'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoriteByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function purchases()
    {
        return $this->hasOne(Purchase::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function getIsFavoriteAttribute()
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->favoriteByUsers()->where('user_id', Auth::id())->exists();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favoriteByUsers()->count();
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function getIsSoldAttribute()
    {
        return $this->purchases()->exists();
    }
}
