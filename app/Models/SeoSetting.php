<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Global website settings (key => value).
 *
 * These are the *system defaults*: site name, default social image, analytics
 * IDs, business contact details and so on. They are deliberately separate from
 * page content - a page never falls back to marketing copy from here.
 *
 * Sensitive values (analytics IDs, verification codes) are public identifiers,
 * never secrets. Real secrets stay in .env.
 */
class SeoSetting extends Model
{
    /**
     * key => [label, default, group, type]
     *
     * type: text | textarea | toggle | select | image
     *
     * @var array<string, array{label: string, default: string, group: string, type: string, options?: array<string, string>}>
     */
    public const DEFAULTS = [
        // --- Branding & identity -------------------------------------------
        'site_title' => [
            'label' => 'Site Title (used as a suffix on inner pages)',
            'default' => 'Anuj Kumar Wedding Event Planners',
            'group' => 'Branding',
            'type' => 'text',
        ],
        'site_tagline' => [
            'label' => 'Site Tagline',
            'default' => '',
            'group' => 'Branding',
            'type' => 'text',
        ],
        'site_logo' => [
            'label' => 'Logo (upload or URL)',
            'default' => '',
            'group' => 'Branding',
            'type' => 'image',
        ],
        'site_favicon' => [
            'label' => 'Favicon (upload or URL)',
            'default' => '',
            'group' => 'Branding',
            'type' => 'image',
        ],

        // --- Default SEO ----------------------------------------------------
        'home_meta_title' => [
            'label' => 'Home Page Meta Title',
            'default' => 'Anuj Kumar Wedding Event Planners | Wedding & Event Decoration in Uttar Pradesh',
            'group' => 'Default SEO',
            'type' => 'text',
        ],
        'home_meta_description' => [
            'label' => 'Home Page Meta Description',
            'default' => 'Wedding and event decoration by Anuj Kumar Wedding Event Planners — haldi, mehendi, stage, entry, balloon and floral setups across our service areas in Uttar Pradesh.',
            'group' => 'Default SEO',
            'type' => 'textarea',
        ],
        'meta_keywords' => [
            'label' => 'Default Meta Keywords',
            'default' => '',
            'group' => 'Default SEO',
            'type' => 'textarea',
        ],
        'og_image' => [
            'label' => 'Default Social Share Image (1200 x 630 recommended)',
            'default' => '',
            'group' => 'Default SEO',
            'type' => 'image',
        ],
        'twitter_card' => [
            'label' => 'Default Twitter/X Card Type',
            'default' => 'summary_large_image',
            'group' => 'Default SEO',
            'type' => 'select',
            'options' => [
                'summary' => 'summary',
                'summary_large_image' => 'summary_large_image',
            ],
        ],
        'default_robots_index' => [
            'label' => 'Index new pages by default',
            'default' => '1',
            'group' => 'Default SEO',
            'type' => 'toggle',
        ],
        'default_robots_follow' => [
            'label' => 'Follow links by default',
            'default' => '1',
            'group' => 'Default SEO',
            'type' => 'toggle',
        ],

        // --- Contact / business --------------------------------------------
        'phone' => [
            'label' => 'Business Phone',
            'default' => '',
            'group' => 'Business',
            'type' => 'text',
        ],
        'whatsapp_number' => [
            'label' => 'WhatsApp Number (with country code, digits only)',
            'default' => '',
            'group' => 'Business',
            'type' => 'text',
        ],
        'whatsapp_default_message' => [
            'label' => 'WhatsApp Prefilled Message',
            'default' => 'Hello! I would like to know more about your decoration services.',
            'group' => 'Business',
            'type' => 'textarea',
        ],
        'enquiry_notification_email' => [
            'label' => 'Enquiry Notification Email (empty = Business Email)',
            'default' => '',
            'group' => 'Business',
            'type' => 'text',
        ],
        'email' => [
            'label' => 'Business Email',
            'default' => '',
            'group' => 'Business',
            'type' => 'text',
        ],
        'address' => [
            'label' => 'Business Address',
            'default' => '',
            'group' => 'Business',
            'type' => 'textarea',
        ],
        'address_locality' => [
            'label' => 'Address Locality (city)',
            'default' => '',
            'group' => 'Business',
            'type' => 'text',
        ],
        'address_region' => [
            'label' => 'Address Region (state)',
            'default' => '',
            'group' => 'Business',
            'type' => 'text',
        ],
        'postal_code' => [
            'label' => 'Postal Code',
            'default' => '',
            'group' => 'Business',
            'type' => 'text',
        ],
        'address_country' => [
            'label' => 'Address Country (ISO code)',
            'default' => 'IN',
            'group' => 'Business',
            'type' => 'text',
        ],
        'price_range' => [
            'label' => 'Price Range (e.g. ₹₹)',
            'default' => '',
            'group' => 'Business',
            'type' => 'text',
        ],
        'opening_hours' => [
            'label' => 'Opening Hours (schema.org format, e.g. Mo-Su 09:00-20:00)',
            'default' => '',
            'group' => 'Business',
            'type' => 'text',
        ],
        'business_type' => [
            'label' => 'Schema.org Business Type',
            'default' => 'LocalBusiness',
            'group' => 'Business',
            'type' => 'text',
        ],
        'google_map_url' => [
            'label' => 'Google Map URL',
            'default' => '',
            'group' => 'Business',
            'type' => 'text',
        ],

        // --- Header / footer ------------------------------------------------
        'header_cta_label' => [
            'label' => 'Header CTA Button Text',
            'default' => '',
            'group' => 'Header & Footer',
            'type' => 'text',
        ],
        'header_cta_url' => [
            'label' => 'Header CTA Button Link',
            'default' => '',
            'group' => 'Header & Footer',
            'type' => 'text',
        ],
        'footer_about' => [
            'label' => 'Footer About Text',
            'default' => '',
            'group' => 'Header & Footer',
            'type' => 'textarea',
        ],
        'footer_copyright' => [
            'label' => 'Footer Copyright Text',
            'default' => '',
            'group' => 'Header & Footer',
            'type' => 'text',
        ],
        'footer_working_hours' => [
            'label' => 'Footer Working Hours (display text)',
            'default' => '',
            'group' => 'Header & Footer',
            'type' => 'text',
        ],
        'footer_background_image' => [
            'label' => 'Footer Background Image (upload or URL)',
            'default' => 'assets/images/footer-background.png',
            'group' => 'Header & Footer',
            'type' => 'image',
        ],

        // --- Mega menu -------------------------------------------------------
        'mega_kicker' => [
            'label' => 'Mega Menu Kicker',
            'default' => '',
            'group' => 'Mega Menu',
            'type' => 'text',
        ],
        'mega_title' => [
            'label' => 'Mega Menu Intro Heading',
            'default' => '',
            'group' => 'Mega Menu',
            'type' => 'text',
        ],
        'mega_text' => [
            'label' => 'Mega Menu Intro Text',
            'default' => '',
            'group' => 'Mega Menu',
            'type' => 'textarea',
        ],
        'mega_cta_label' => [
            'label' => 'Mega Menu CTA Label',
            'default' => '',
            'group' => 'Mega Menu',
            'type' => 'text',
        ],
        'mega_cta_url' => [
            'label' => 'Mega Menu CTA Link',
            'default' => '',
            'group' => 'Mega Menu',
            'type' => 'text',
        ],
        'mega_bottom_title' => [
            'label' => 'Mega Menu Bottom Strip Heading',
            'default' => '',
            'group' => 'Mega Menu',
            'type' => 'text',
        ],
        'mega_bottom_text' => [
            'label' => 'Mega Menu Bottom Strip Text',
            'default' => '',
            'group' => 'Mega Menu',
            'type' => 'text',
        ],
        'mega_bottom_link_label' => [
            'label' => 'Mega Menu Bottom Secondary Link Label',
            'default' => '',
            'group' => 'Mega Menu',
            'type' => 'text',
        ],
        'mega_bottom_link_url' => [
            'label' => 'Mega Menu Bottom Secondary Link URL',
            'default' => '',
            'group' => 'Mega Menu',
            'type' => 'text',
        ],
        'mega_bottom_primary_label' => [
            'label' => 'Mega Menu Bottom Primary Button Label',
            'default' => '',
            'group' => 'Mega Menu',
            'type' => 'text',
        ],
        'mega_bottom_primary_url' => [
            'label' => 'Mega Menu Bottom Primary Button URL',
            'default' => '',
            'group' => 'Mega Menu',
            'type' => 'text',
        ],

        // --- Mobile menu -----------------------------------------------------
        'mobile_intro_kicker' => [
            'label' => 'Mobile Menu Intro Kicker',
            'default' => '',
            'group' => 'Mobile Menu',
            'type' => 'text',
        ],
        'mobile_intro_title' => [
            'label' => 'Mobile Menu Intro Heading',
            'default' => '',
            'group' => 'Mobile Menu',
            'type' => 'text',
        ],
        'mobile_enquiry_label' => [
            'label' => 'Mobile Enquiry Card Label',
            'default' => '',
            'group' => 'Mobile Menu',
            'type' => 'text',
        ],
        'mobile_enquiry_title' => [
            'label' => 'Mobile Enquiry Card Heading',
            'default' => '',
            'group' => 'Mobile Menu',
            'type' => 'text',
        ],
        'mobile_enquiry_text' => [
            'label' => 'Mobile Enquiry Card Text',
            'default' => '',
            'group' => 'Mobile Menu',
            'type' => 'textarea',
        ],
        'mobile_enquiry_button_label' => [
            'label' => 'Mobile Enquiry Card Button Label',
            'default' => '',
            'group' => 'Mobile Menu',
            'type' => 'text',
        ],
        'mobile_enquiry_button_url' => [
            'label' => 'Mobile Enquiry Card Button URL',
            'default' => '',
            'group' => 'Mobile Menu',
            'type' => 'text',
        ],

        // --- Analytics & verification --------------------------------------
        'google_analytics_id' => [
            'label' => 'Google Analytics 4 Measurement ID (G-XXXXXXX)',
            'default' => '',
            'group' => 'Analytics',
            'type' => 'text',
        ],
        'google_tag_manager_id' => [
            'label' => 'Google Tag Manager ID (GTM-XXXXXXX)',
            'default' => '',
            'group' => 'Analytics',
            'type' => 'text',
        ],
        'google_site_verification' => [
            'label' => 'Google Search Console Verification Code',
            'default' => '',
            'group' => 'Analytics',
            'type' => 'text',
        ],
        'analytics_require_consent' => [
            'label' => 'Only load analytics after consent',
            'default' => '0',
            'group' => 'Analytics',
            'type' => 'toggle',
        ],

        // --- Sitemap ---------------------------------------------------------
        'sitemap_changefreq' => [
            'label' => 'Sitemap Change Frequency',
            'default' => 'weekly',
            'group' => 'Sitemap',
            'type' => 'select',
            'options' => [
                'daily' => 'daily',
                'weekly' => 'weekly',
                'monthly' => 'monthly',
            ],
        ],

        // --- Section labels (used by every location template) ---------------
        'label_why_choose' => [
            'label' => 'Label: Why Choose Us',
            'default' => 'Why Choose Us',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_state_why_title' => [
            'label' => 'Heading: State about block ({state} placeholder)',
            'default' => 'Wedding and Event Decoration Team in {state}',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_city_why_title' => [
            'label' => 'Heading: City about block ({city} placeholder)',
            'default' => 'Wedding and Event Decoration in {city}',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_state_districts' => [
            'label' => 'Label: State service areas',
            'default' => 'Service Areas',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_state_districts_title' => [
            'label' => 'Heading: State service areas ({state} placeholder)',
            'default' => 'Cities, Towns and Villages of {state}',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_state_districts_text' => [
            'label' => 'Text: State service areas',
            'default' => '',
            'group' => 'Section Labels',
            'type' => 'textarea',
        ],
        'label_our_services' => [
            'label' => 'Label: Our Services',
            'default' => 'Our Services',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_services_title' => [
            'label' => 'Heading: Services grid',
            'default' => 'Decoration Services',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_services_text' => [
            'label' => 'Text: Services grid',
            'default' => '',
            'group' => 'Section Labels',
            'type' => 'textarea',
        ],
        'label_city_services_title' => [
            'label' => 'Heading: City services grid ({city} placeholder)',
            'default' => 'Decoration Services Available in {city}',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_nearby_areas' => [
            'label' => 'Label: Nearby areas',
            'default' => 'Nearby Areas',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_nearby_title' => [
            'label' => 'Heading: Nearby areas ({city} placeholder)',
            'default' => 'Areas Near {city}',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_nearby_text' => [
            'label' => 'Text: Nearby areas',
            'default' => '',
            'group' => 'Section Labels',
            'type' => 'textarea',
        ],
        'label_other_cities' => [
            'label' => 'Label: Other cities',
            'default' => 'Other Cities',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_other_cities_title' => [
            'label' => 'Heading: Other cities ({state} placeholder)',
            'default' => 'Other Cities in {state}',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_other_cities_text' => [
            'label' => 'Text: Other cities',
            'default' => '',
            'group' => 'Section Labels',
            'type' => 'textarea',
        ],
        'label_faq' => [
            'label' => 'Label: FAQ',
            'default' => 'FAQ',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_faq_title' => [
            'label' => 'Heading: FAQ block',
            'default' => 'Frequently Asked Questions',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_cta_title' => [
            'label' => 'Heading: Contact CTA band',
            'default' => 'Book Decoration for Your Function',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_cta_text' => [
            'label' => 'Text: Contact CTA band',
            'default' => '',
            'group' => 'Section Labels',
            'type' => 'textarea',
        ],
        'label_service_detail' => [
            'label' => 'Label: Service detail block',
            'default' => 'Service Detail',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_more_services' => [
            'label' => 'Label: More services',
            'default' => 'More Services',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_more_services_title' => [
            'label' => 'Heading: More services ({city} placeholder)',
            'default' => 'More Decoration Services in {city}',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_locality_areas' => [
            'label' => 'Label: Localities block',
            'default' => 'Localities & Areas',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_locality_areas_title' => [
            'label' => 'Heading: Localities block ({city} placeholder)',
            'default' => 'Areas We Cover in {city}',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_service_areas' => [
            'label' => 'Footer heading: Service areas',
            'default' => 'Our Service Areas',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_footer_contact' => [
            'label' => 'Footer heading: Contact column',
            'default' => 'Contact Us',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_footer_legal' => [
            'label' => 'Footer heading: Legal links',
            'default' => 'Legal',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_view_all_areas' => [
            'label' => 'Footer link: View all areas',
            'default' => 'View all areas',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_view_all_services' => [
            'label' => 'Mobile menu link: View all services',
            'default' => 'View All Services',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_years_of' => [
            'label' => 'Home about: "Years Of" label',
            'default' => 'Years Of',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_experience' => [
            'label' => 'Home about: "Experience" label',
            'default' => 'Experience',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_beautifully_planned' => [
            'label' => 'Home about: small badge label',
            'default' => 'Beautifully Planned',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_talk_to_us' => [
            'label' => 'Hero / contact: "Talk to us" label',
            'default' => 'Talk to Us',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'service_areas_intro' => [
            'label' => 'Service areas: short intro text',
            'default' => 'We accept wedding and event decoration bookings in the following service areas.',
            'group' => 'Section Labels',
            'type' => 'textarea',
        ],
        'service_areas_note' => [
            'label' => 'Service areas: note below the list',
            'default' => 'Outstation event requirements can be discussed based on venue, event date and availability.',
            'group' => 'Section Labels',
            'type' => 'textarea',
        ],
        'label_discuss_your_event' => [
            'label' => 'Button: Discuss Your Event',
            'default' => 'Discuss Your Event',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_contact_our_team' => [
            'label' => 'Button: Contact Our Team',
            'default' => 'Contact Our Team',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_open_google_maps' => [
            'label' => 'Button: Open Google Maps',
            'default' => 'Open Google Maps',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_nearby_cities' => [
            'label' => 'Service area panel: Nearby cities label',
            'default' => 'Nearby Cities & Areas',
            'group' => 'Section Labels',
            'type' => 'text',
        ],
        'label_check_event_location' => [
            'label' => 'Button: Check Your Event Location',
            'default' => 'Check Your Event Location',
            'group' => 'Section Labels',
            'type' => 'text',
        ],

        // --- Built-in page text ---------------------------------------------
        // These are only default values. The admin can edit or clear every one
        // of them from Global Settings; public templates never contain their
        // own marketing fallback copy.
        'content_home_services_empty' => [
            'label' => 'Home: text when no services are published',
            'default' => 'Our decoration services will be listed here soon.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_home_faq_intro' => [
            'label' => 'Home: FAQ intro text',
            'default' => 'Answers to common questions about wedding and event decoration services.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_home_contact_intro' => [
            'label' => 'Home: enquiry form intro text',
            'default' => 'Fill in the details below and our team will get back to you with a customized quote.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_services_empty' => [
            'label' => 'Services page: text when no services are published',
            'default' => 'Our decoration services will be listed here soon.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_blog_cta_title' => [
            'label' => 'Blog detail: CTA heading',
            'default' => 'Planning a Celebration?',
            'group' => 'Page Text',
            'type' => 'text',
        ],
        'content_blog_cta_text' => [
            'label' => 'Blog detail: CTA text',
            'default' => 'Share your event date, city and requirements with our team.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_sitemap_services_intro' => [
            'label' => 'Sitemap: services intro',
            'default' => 'Explore our wedding and event decoration services.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_sitemap_about_intro' => [
            'label' => 'Sitemap: about intro',
            'default' => 'Learn about Anuj Kumar Wedding Event Planners.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_sitemap_all_services_intro' => [
            'label' => 'Sitemap: all services intro',
            'default' => 'Explore our complete decoration services.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_sitemap_gallery_intro' => [
            'label' => 'Sitemap: gallery intro',
            'default' => 'Explore wedding and event decoration inspiration.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_sitemap_packages_intro' => [
            'label' => 'Sitemap: packages intro',
            'default' => 'Explore decoration packages for different events.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_sitemap_blogs_intro' => [
            'label' => 'Sitemap: blog intro',
            'default' => 'Read wedding and event decoration ideas.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_sitemap_faq_intro' => [
            'label' => 'Sitemap: FAQ intro',
            'default' => 'Find answers to common decoration questions.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_sitemap_contact_intro' => [
            'label' => 'Sitemap: contact intro',
            'default' => 'Discuss your wedding or event requirements.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_gallery_empty' => [
            'label' => 'Gallery page: empty state',
            'default' => 'Our gallery is being updated.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_packages_empty' => [
            'label' => 'Packages page: empty state',
            'default' => 'No packages are published yet.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_guide_wedding_text' => [
            'label' => 'Guide: wedding decoration description',
            'default' => 'Wedding stages, floral decoration, entrances and venue styling.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_guide_haldi_text' => [
            'label' => 'Guide: haldi decoration description',
            'default' => 'Colourful, vibrant and traditional Haldi decoration ideas.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_guide_mehendi_text' => [
            'label' => 'Guide: mehendi decoration description',
            'default' => 'Elegant Mehendi themes, floral details and colourful setups.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_guide_balloon_text' => [
            'label' => 'Guide: balloon decoration description',
            'default' => 'Balloon decoration, counters and celebration styling.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_guide_before_event' => [
            'label' => 'Guide: event type step',
            'default' => 'Wedding, Haldi, Mehendi, birthday or another celebration.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_guide_venue_space' => [
            'label' => 'Guide: venue space step',
            'default' => 'Consider available space, entrance, stage and guest movement.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_guide_theme' => [
            'label' => 'Guide: theme step',
            'default' => 'Select the overall visual mood and decoration direction.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_guide_colours' => [
            'label' => 'Guide: colours step',
            'default' => 'Choose colours that complement the event and venue.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_guide_stage' => [
            'label' => 'Guide: stage and backdrop step',
            'default' => 'Decide the main visual focal point of your celebration.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_guide_entry' => [
            'label' => 'Guide: entrance and details step',
            'default' => 'Think about entry decoration, counters, flowers and smaller details.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
        'content_guide_booking' => [
            'label' => 'Guide: booking step',
            'default' => 'Share your date early so availability can be discussed.',
            'group' => 'Page Text',
            'type' => 'textarea',
        ],
    ];

    /**
     * @var list<string>
     */
    protected $fillable = ['key', 'value', 'group'];

    /**
     * @return array<string, string>
     */
    public static function values(): array
    {
        return Cache::rememberForever('seo-settings', function (): array {
            $values = static::query()->pluck('value', 'key')->all();

            return array_map(
                fn (mixed $value): string => (string) ($value ?? ''),
                array_merge(static::defaults(), $values),
            );
        });
    }

    public static function value(string $key, ?string $default = null): ?string
    {
        $value = static::values()[$key] ?? null;

        if (filled($value)) {
            return $value;
        }

        return $default ?? (static::defaults()[$key] ?? null);
    }

    public static function put(string $key, ?string $value, string $group = 'general'): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);

        static::flush();
    }

    public static function flush(): void
    {
        Cache::forget('seo-settings');
    }

    /**
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return array_map(fn (array $setting): string => (string) $setting['default'], static::DEFAULTS);
    }

    /**
     * Settings grouped for the admin settings page.
     *
     * @return array<string, array<string, array{label: string, default: string, group: string, type: string, options?: array<string, string>}>>
     */
    public static function grouped(): array
    {
        $grouped = [];

        foreach (static::DEFAULTS as $key => $setting) {
            $grouped[$setting['group']][$key] = $setting;
        }

        return $grouped;
    }
}
