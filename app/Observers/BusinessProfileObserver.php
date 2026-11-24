<?php

namespace App\Observers;

use App\Models\BusinessProfile;
use Illuminate\Support\Facades\Cache;

/**
 * Business profile (naam, phone, address waghera) save/delete hone par
 * uska cached array clear kar deta hai, taaki website turant naya
 * detail dikhaye.
 */
class BusinessProfileObserver
{
    public function saved(BusinessProfile $profile): void
    {
        Cache::forget('site-business-profile');
    }

    public function deleted(BusinessProfile $profile): void
    {
        Cache::forget('site-business-profile');
    }
}
