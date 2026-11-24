<?php

namespace Database\Seeders;

use App\Models\SeoSetting;
use Illuminate\Database\Seeder;

/**
 * Seeds the default header, mega menu, mobile menu and footer copy.
 *
 * Values are written only when the setting does not exist yet, so anything the
 * admin has edited or intentionally cleared is preserved.
 */
class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'header_cta_label' => 'Get a Quote',
            'header_cta_url' => '/contact-us',
            'footer_working_hours' => 'Open all days, 9:00 AM to 8:00 PM',

            'mega_kicker' => 'Wedding & Event Decoration',
            'mega_title' => 'Everything You Need for a Beautiful Celebration',
            'mega_text' => 'Explore decoration services for weddings, haldi, mehendi, birthdays and complete events.',
            'mega_cta_label' => 'View All Services',
            'mega_cta_url' => '/services',
            'mega_bottom_title' => 'Not sure what you need?',
            'mega_bottom_text' => 'Share your date, city and venue — we will suggest the right decoration plan.',
            'mega_bottom_link_label' => 'See Our Work',
            'mega_bottom_link_url' => '/gallery',
            'mega_bottom_primary_label' => 'Get a Free Quote',
            'mega_bottom_primary_url' => '/contact-us',

            'mobile_intro_kicker' => 'Welcome to',
            'mobile_intro_title' => 'Anuj Kumar Wedding Event Planners',
            'mobile_enquiry_label' => 'Free Quotation',
            'mobile_enquiry_title' => 'Planning a Celebration?',
            'mobile_enquiry_text' => 'Share your event date, city and requirements. Our team will get back with ideas and a quotation.',
            'mobile_enquiry_button_label' => 'Send an Enquiry',
            'mobile_enquiry_button_url' => '/contact-us',
        ];

        foreach ($settings as $key => $value) {
            if (SeoSetting::query()->where('key', $key)->exists()) {
                continue;
            }

            SeoSetting::put($key, $value, SeoSetting::DEFAULTS[$key]['group'] ?? 'general');
        }

        SeoSetting::flush();
    }
}
