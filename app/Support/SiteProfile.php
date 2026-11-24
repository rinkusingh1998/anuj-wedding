<?php

namespace App\Support;

use App\Models\BusinessProfile;
use App\Models\SeoSetting;
use Illuminate\Support\Facades\Cache;

/**
 * Website business profile (name, phone, address, ...).
 *
 * Source order: the Business Profile record, then the global settings, then
 * nothing. No contact detail is invented - an empty phone simply hides the
 * call buttons instead of showing a fake number.
 *
 * Everything is cached as a plain array (caching an Eloquent model would break
 * on file/redis stores).
 */
final class SiteProfile
{
    /**
     * @var list<string>
     */
    private const FIELDS = [
        'business_name', 'short_name', 'tagline',
        'logo', 'mobile_logo', 'favicon', 'og_image',
        'phone', 'whatsapp', 'email', 'address', 'google_map_url',
        'facebook_url', 'instagram_url', 'youtube_url',
        'footer_about', 'footer_copyright',
        'address_locality', 'address_region', 'postal_code', 'address_country',
        'price_range', 'opening_hours',
    ];

    /**
     * Business settings that also exist in the global settings table.
     *
     * @var array<string, string>
     */
    private const SETTING_MAP = [
        'business_name' => 'site_title',
        'tagline' => 'site_tagline',
        'logo' => 'site_logo',
        'favicon' => 'site_favicon',
        'og_image' => 'og_image',
        'phone' => 'phone',
        'whatsapp' => 'whatsapp_number',
        'email' => 'email',
        'address' => 'address',
        'google_map_url' => 'google_map_url',
        'footer_about' => 'footer_about',
        'footer_copyright' => 'footer_copyright',
        'address_locality' => 'address_locality',
        'address_region' => 'address_region',
        'postal_code' => 'postal_code',
        'address_country' => 'address_country',
        'price_range' => 'price_range',
        'opening_hours' => 'opening_hours',
    ];

    public function name(): string
    {
        return $this->value('business_name') ?: (string) SeoSetting::value('site_title', config('app.name'));
    }

    public function shortName(): string
    {
        return $this->value('short_name') ?: $this->name();
    }

    public function tagline(): string
    {
        return $this->value('tagline');
    }

    public function phone(): string
    {
        return $this->value('phone');
    }

    /**
     * Digits only, for wa.me links.
     */
    public function whatsapp(): string
    {
        return preg_replace('/\D/', '', $this->value('whatsapp') ?: $this->phone()) ?: '';
    }

    /**
     * Ready-to-use WhatsApp link with a prefilled message.
     *
     * Desktop visitors are sent to WhatsApp Web, while phones and tablets use
     * the wa.me deep link. Both links use the same E.164 number and message.
     */
    public function whatsappUrl(?string $message = null): string
    {
        $number = $this->whatsapp();

        if ($number === '') {
            return '';
        }

        $message ??= (string) SeoSetting::value('whatsapp_default_message');
        $number = '+'.$number;
        $query = filled($message) ? 'text='.rawurlencode($message) : '';

        if ($this->isMobileDevice()) {
            return 'https://wa.me/'.$number
                .($query !== '' ? '?'.$query : '');
        }

        return 'https://web.whatsapp.com/send?phone='.$number
            .($query !== '' ? '&'.$query : '');
    }

    /**
     * Phone / tablet detection for selecting the correct WhatsApp deep link.
     */
    private function isMobileDevice(): bool
    {
        $userAgent = (string) request()->userAgent();

        return preg_match(
            '/(android|iphone|ipad|ipod|mobile|opera mini|iemobile|blackberry|windows phone)/i',
            $userAgent,
        ) === 1;
    }

    public function email(): string
    {
        return $this->value('email');
    }

    public function address(): string
    {
        return $this->value('address');
    }

    public function footerAbout(): string
    {
        return $this->value('footer_about');
    }

    public function footerCopyright(): string
    {
        return $this->value('footer_copyright') ?: $this->name();
    }

    public function openingHours(): string
    {
        return $this->value('opening_hours');
    }

    public function priceRange(): string
    {
        return $this->value('price_range');
    }

    public function logo(): string
    {
        return $this->image('logo', asset('assets/logo/logo2.png'));
    }

    public function mobileLogo(): string
    {
        return $this->image('mobile_logo', $this->logo());
    }

    public function ogImage(): string
    {
        $image = MediaLibrary::url($this->value('og_image'));

        return $image ?? MediaLibrary::default();
    }

    public function favicon(): string
    {
        return $this->image('favicon', asset('assets/logo/fevicon.png'));
    }

    public function mapUrl(): string
    {
        $url = $this->value('google_map_url');

        if (filled($url)) {
            return $url;
        }

        $address = $this->address();

        return filled($address)
            ? 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($address)
            : '';
    }

    /**
     * @return array<string, string>
     */
    public function social(): array
    {
        return array_filter([
            'facebook' => $this->value('facebook_url'),
            'instagram' => $this->value('instagram_url'),
            'youtube' => $this->value('youtube_url'),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function business(): array
    {
        return Cache::rememberForever('site-business-profile', function (): array {
            $profile = BusinessProfile::query()->first();
            $data = $profile?->only(self::FIELDS) ?? [];

            $values = array_map(
                fn (mixed $value): string => (string) ($value ?? ''),
                $data,
            );

            // Fill anything missing from the global website settings.
            foreach (self::SETTING_MAP as $field => $settingKey) {
                if (($values[$field] ?? '') === '') {
                    $values[$field] = (string) (SeoSetting::value($settingKey) ?? '');
                }
            }

            return $values;
        });
    }

    public function value(string $key): string
    {
        return (string) ($this->business()[$key] ?? '');
    }

    /**
     * Resolve a stored image value to a public URL, falling back to a bundled
     * default asset only when nothing has been configured.
     */
    private function image(string $key, string $fallback): string
    {
        $value = $this->value($key);

        if ($value === '') {
            return $fallback;
        }

        if (str_starts_with($value, 'assets/') || str_starts_with($value, 'storage/')) {
            return asset($value);
        }

        return MediaLibrary::url($value) ?? $fallback;
    }
}
