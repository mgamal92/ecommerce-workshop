<?php

namespace App\Models;

use App\Traits\InteractsWithHashedMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Customer extends Authenticatable implements HasMedia
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use InteractsWithHashedMedia;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cart()
    {
        return $this->hasOne(Cart::class, 'customer_id');
    }

    public function address()
    {
        return $this->hasMany(Address::class, 'customer_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('customersAvatar')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200);
            });
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function category()
    {
        return  $this->belongsToMany(Category::class, 'customer_category', 'customer_id', 'category_id');
    }

    public function lang()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
