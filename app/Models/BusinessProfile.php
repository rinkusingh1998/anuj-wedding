<?php

namespace App\Models;

use App\Observers\BusinessProfileObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(BusinessProfileObserver::class)]
class BusinessProfile extends Model
{
    protected $fillable = [
        'business_name',
        'short_name',
        'tagline',

        'logo',
        'mobile_logo',
        'favicon',
        'og_image',

        'phone',
        'whatsapp',
        'email',
        'address',
        'google_map_url',

        'facebook_url',
        'instagram_url',
        'youtube_url',

        'footer_about',
        'footer_copyright',
    ];
}
