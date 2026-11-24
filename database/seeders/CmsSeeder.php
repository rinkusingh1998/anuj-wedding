<?php

namespace Database\Seeders;

use App\Models\BusinessProfile;
use App\Models\City;
use App\Models\Page;
use App\Models\SeoSetting;
use App\Models\Service;
use App\Models\State;
use App\Support\MediaLibrary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds the CMS content, CDN images and English SEO copy.
 *
 * The website reads everything from the database at runtime. This seeder only
 * provides sensible starting values for a fresh install and can safely be run
 * again without destroying admin edits (meta values are only filled when they
 * are empty).
 */
class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSeoSettings();
        $this->seedBusinessProfile();
        $this->seedPages();
        $this->seedServices();
        $this->seedStates();
        $this->seedCities();
    }

    private function seedSeoSettings(): void
    {
        $settings = [
            'site_title' => 'Anuj Kumar Wedding Event Planners',
            'site_tagline' => 'Wedding & Event Decoration across Uttar Pradesh',
            'home_meta_title' => 'Wedding & Event Decoration in Uttar Pradesh | Anuj Kumar Wedding Event Planners',
            'home_meta_description' => 'Wedding and event decoration by Anuj Kumar Wedding Event Planners — haldi, mehendi, stage, entry, balloon and floral setups across service areas in Uttar Pradesh. Call +91 73020 15048 for booking.',
            'meta_keywords' => 'wedding decoration uttar pradesh, wedding decorator near me, haldi decoration, mehendi decoration, balloon decoration, flower decoration, wedding stage decoration',
            'og_image' => MediaLibrary::image('home'),
            'sitemap_changefreq' => 'weekly',
            'whatsapp_number' => '917302015048',
        ];

        foreach ($settings as $key => $value) {
            SeoSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general'],
            );
        }

        SeoSetting::flush();
    }

    private function seedBusinessProfile(): void
    {
        BusinessProfile::query()->updateOrCreate(
            ['id' => 1],
            [
                'business_name' => 'Anuj Kumar Wedding Event Planners',
                'short_name' => 'Anuj Kumar Events',
                'tagline' => 'Wedding & Event Decoration Specialists in Uttar Pradesh',
                'logo' => null,
                'mobile_logo' => null,
                'favicon' => null,
                'og_image' => MediaLibrary::image('home'),
                'phone' => '+91 73020 15048',
                'whatsapp' => '917302015048',
                'email' => 'info@anujkumar.com',
                'address' => 'Roopwas Pachgain, Sikandrabad, Bulandshahr - 203131, Uttar Pradesh, India',
                'google_map_url' => 'https://www.google.com/maps/search/?api=1&query=Sikandrabad+Bulandshahr+Uttar+Pradesh',
                'facebook_url' => 'https://www.facebook.com/',
                'instagram_url' => 'https://www.instagram.com/',
                'youtube_url' => 'https://www.youtube.com/',
                'footer_about' => 'Anuj Kumar Wedding Event Planners creates wedding, haldi, mehendi, birthday and event decoration across its published service areas in Uttar Pradesh.',
                'footer_copyright' => '© '.date('Y').' Anuj Kumar Wedding Event Planners. All rights reserved.',
            ],
        );

        cache()->forget('site-business-profile');
    }

    private function seedPages(): void
    {
        foreach ($this->pageDefinitions() as $slug => $definition) {
            $page = Page::query()->where('slug', $slug)->first();

            if (! $page) {
                $page = Page::query()->create([
                    'name' => Str::headline($slug),
                    'slug' => $slug,
                    'is_visible' => true,
                    'priority' => 50,
                    'sort_order' => 0,
                ]);
            }

            $page->forceFill([
                'image' => $page->image ?: MediaLibrary::image($slug),
                'og_image' => $page->og_image ?: MediaLibrary::image($slug),
                'gallery' => $page->gallery ?: $this->galleryItems($slug),
                'content' => $page->content ?: ($definition['content'] ?? []),
                'faqs' => $page->faqs ?: ($definition['faqs'] ?? []),
                'meta_title' => $page->meta_title ?: ($definition['meta_title'] ?? null),
                'meta_description' => $page->meta_description ?: ($definition['meta_description'] ?? null),
                'meta_keywords' => $page->meta_keywords ?: ($definition['meta_keywords'] ?? null),
                'heading' => $page->heading ?: ($definition['heading'] ?? null),
                'sub_heading' => $page->sub_heading ?: ($definition['sub_heading'] ?? null),
                'intro_content' => $page->intro_content ?: ($definition['intro_content'] ?? null),
            ])->save();
        }
    }

    private function seedServices(): void
    {
        foreach (Service::query()->get() as $service) {
            $service->forceFill([
                'image' => $service->image ?: MediaLibrary::image($service->slug),
                'og_image' => $service->og_image ?: MediaLibrary::image($service->slug),
                'gallery' => $service->gallery ?: $this->galleryItems($service->slug, 4),
            ])->save();
        }
    }

    private function seedStates(): void
    {
        $uttarPradesh = State::query()->where('slug', 'uttar-pradesh')->first();

        if ($uttarPradesh) {
            $uttarPradesh->forceFill([
                'is_visible' => true,
                'is_default' => true,
                'image' => $uttarPradesh->image ?: MediaLibrary::image('uttar-pradesh'),
                'og_image' => $uttarPradesh->og_image ?: MediaLibrary::image('uttar-pradesh'),
                'gallery' => $uttarPradesh->gallery ?: $this->galleryItems('uttar-pradesh', 3),
                'meta_title' => $uttarPradesh->meta_title ?: 'Wedding & Event Decoration in Uttar Pradesh | Haldi, Mehendi & Balloon Decoration - Anuj Kumar Events',
                'meta_description' => $uttarPradesh->meta_description ?: 'Wedding and event decoration across our published service areas in Uttar Pradesh - haldi, mehendi, chunni ceremony, balloon, flower, stage, entry gate and complete wedding setups. Call +91 73020 15048 for booking.',
                'meta_keywords' => $uttarPradesh->meta_keywords ?: 'wedding decoration in uttar pradesh, wedding decorator uttar pradesh, haldi decoration up, mehendi decoration up, balloon decoration uttar pradesh, wedding stage decoration up',
                'heading' => $uttarPradesh->heading ?: 'Wedding & Event Decoration in Uttar Pradesh',
                'sub_heading' => $uttarPradesh->sub_heading ?: 'Serving every city, tehsil and village of Uttar Pradesh',
                'intro_content' => $uttarPradesh->intro_content ?: $this->uttarPradeshIntro(),
                'about_content' => $uttarPradesh->about_content ?: $this->uttarPradeshAbout(),
                'faqs' => $uttarPradesh->faqs ?: $this->uttarPradeshFaqs(),
            ])->save();
        }

        // Delhi stays hidden until the admin explicitly turns it on.
        State::query()->where('slug', 'delhi')->update(['is_visible' => false]);
    }

    private function seedCities(): void
    {
        $state = State::query()->where('slug', 'uttar-pradesh')->first();

        if (! $state) {
            return;
        }

        $images = MediaLibrary::images('city');

        City::query()
            ->where('state_id', $state->id)
            ->orderBy('id')
            ->chunkById(100, function ($cities) use ($images): void {
                foreach ($cities as $index => $city) {
                    $image = $images[$city->id % count($images)];

                    $city->forceFill([
                        'image' => $city->image ?: $image,
                        'og_image' => $city->og_image ?: $image,
                        'meta_title' => $city->meta_title ?: "Wedding Decoration in {$city->name} | Haldi, Mehendi & Balloon Decoration - Anuj Kumar Events",
                        'meta_description' => $city->meta_description ?: "Wedding and event decoration service in {$city->name}, {$city->district}, Uttar Pradesh. Haldi, mehendi, balloon, flower decoration, stage, entry gate and complete wedding setup. Call +91 73020 15048 for booking.",
                        'meta_keywords' => $city->meta_keywords ?: "wedding decoration in {$city->name}, wedding decorator {$city->name}, haldi decoration {$city->name}, mehendi decoration {$city->name}, balloon decoration {$city->name}, event decoration {$city->name}",
                    ])->save();
                }
            });
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function pageDefinitions(): array
    {
        return [
            'home' => [
                'meta_title' => 'Wedding & Event Decoration in Uttar Pradesh | Anuj Kumar Wedding Event Planners',
                'meta_description' => 'Wedding and event decoration by Anuj Kumar Wedding Event Planners — haldi, mehendi, stage, entry, balloon and floral setups across service areas in Uttar Pradesh. Call +91 73020 15048 for booking.',
                'meta_keywords' => 'wedding decoration uttar pradesh, wedding decorator near me, haldi decoration, mehendi decoration, balloon decoration, flower decoration, wedding stage decoration',
                'heading' => 'Beautiful Celebrations, <span>Beautifully Decorated</span>',
                'sub_heading' => 'Wedding & Event Decoration in Uttar Pradesh',
                'intro_content' => '<p>Thoughtfully designed wedding and event decoration that turns every celebration into a memorable experience across Uttar Pradesh.</p>',
                'content' => $this->homeContent(),
                'faqs' => $this->homeFaqs(),
                'faq' => null,
            ],
            'about-us' => [
                'meta_title' => 'About Us | Anuj Kumar Wedding Event Planners',
                'meta_description' => 'Learn about Anuj Kumar Wedding Event Planners - a professional wedding and event decoration team serving its published service areas in Uttar Pradesh.',
                'meta_keywords' => 'about anuj kumar wedding event planners, wedding decorator uttar pradesh, event decoration team bulandshahr',
                'heading' => 'Wedding & Event Decoration <span>Designed Around Your Celebration</span>',
                'intro_content' => 'Anuj Kumar Wedding Event Planners creates elegant, creative and customized wedding and event decoration experiences across Uttar Pradesh. We bring together thoughtful decoration, beautiful themes and organized execution for celebrations that deserve to be remembered.',
                'content' => [
                    'about_story' => '<p>Anuj Kumar Wedding Event Planners is a wedding and event decoration company based in <strong>{address}</strong>. We specialize in creating attractive and personalized decoration setups for weddings, ceremonies, birthdays, family functions and special celebrations.</p><p>We believe that decoration should complement the occasion rather than simply fill a space. That is why our approach considers the venue, theme, colors, occasion and personal preferences while planning the overall decoration experience.</p><p>From <a href="/services/haldi-decoration">Haldi Decoration</a> and <a href="/services/mehendi-decoration">Mehendi Decoration</a> to <a href="/services/engagement-decoration">Engagement Decoration</a>, <a href="/services/balloon-decoration">Balloon Decoration</a>, Chunni Ceremony Decoration, Family Function Decoration, Fogg & Matka and Complete Event Decoration, we focus on creating a coordinated look for every celebration.</p>',
                ],
            ],
            'services' => [
                'meta_title' => 'Wedding & Event Decoration Services in Uttar Pradesh | Anuj Kumar Events',
                'meta_description' => 'Explore our complete range of wedding and event decoration services — haldi, mehendi, chunni ceremony, balloon, flower, stage, entry gate, wedding counters and complete event decoration.',
                'meta_keywords' => 'wedding decoration services, haldi decoration, mehendi decoration, balloon decoration, flower decoration, stage decoration',
                'heading' => 'Complete Decoration Services <span>For Every Celebration</span>',
                'intro_content' => 'Explore our complete range of wedding and event decoration services, thoughtfully designed for Haldi, Mehendi, Chunni ceremonies, weddings, parties and special events. Choose the decoration style that perfectly matches your celebration.',
            ],
            'gallery' => [
                'meta_title' => 'Wedding & Event Decoration Gallery | Anuj Kumar Events',
                'meta_description' => 'Browse real photos of our wedding, haldi, mehendi, birthday and event decoration setups across Uttar Pradesh.',
                'meta_keywords' => 'wedding decoration gallery, decoration photos, haldi decoration images, wedding stage photos',
                'heading' => 'Beautiful Celebrations <span>Beautifully Decorated</span>',
                'intro_content' => 'Explore our collection of beautiful wedding and event decoration ideas, featuring elegant wedding stages, Haldi setups, Mehendi decoration, floral arrangements, balloon decoration and complete event styling for memorable celebrations.',
            ],
            'packages' => [
                'meta_title' => 'Wedding & Event Decoration Packages | Anuj Kumar Events',
                'meta_description' => 'Compare wedding and event decoration packages for every budget — from small home functions to grand banquet setups. Transparent pricing and fully customisable themes.',
                'meta_keywords' => 'wedding decoration packages, decoration price, wedding decor package, event decoration rates',
                'heading' => 'Beautiful Decoration <span>Packages for Every Celebration</span>',
                'intro_content' => 'Explore thoughtfully designed wedding and event decoration packages for Haldi, Mehendi, birthdays, ceremonies and complete celebrations. Choose a package that suits your occasion, style and decoration requirements.',
            ],
            'blogs' => [
                'meta_title' => 'Wedding & Event Decoration Ideas Blog | Anuj Kumar Events',
                'meta_description' => 'Decoration ideas, theme tips and planning guides for weddings, haldi, mehendi and events from our wedding decoration team in Uttar Pradesh.',
                'meta_keywords' => 'wedding decoration ideas, decoration blog, wedding planning tips, mehendi decoration ideas',
                'heading' => 'Wedding & Event Decoration <span>Ideas, Inspiration & Planning Tips</span>',
                'intro_content' => 'Discover beautiful wedding decoration ideas, Haldi and Mehendi decoration inspiration, flower decoration trends, event planning tips and creative celebration ideas from Anuj Kumar Wedding Event Planners.',
            ],
            'faq' => [
                'meta_title' => 'Wedding Decoration FAQs | Anuj Kumar Events',
                'meta_description' => 'Answers to common questions about wedding and event decoration — services, pricing, customisation, booking, service areas and same-day decoration.',
                'meta_keywords' => 'wedding decoration faq, decoration questions, wedding decorator answers',
                'heading' => 'Frequently Asked Questions <span>About Wedding & Event Decoration</span>',
                'intro_content' => 'Find answers to common questions about our wedding decoration, Haldi, Mehendi, Chunni ceremony, flower, balloon and complete event decoration services. Learn more about our services, customization options, planning process and service areas before planning your celebration.',
                'faqs' => $this->faqPageFaqs(),
            ],
            'locations' => [
                'meta_title' => 'Service Areas | Wedding Decoration across Uttar Pradesh',
                'meta_description' => 'All cities, towns and villages of Uttar Pradesh where Anuj Kumar Wedding Event Planners provides wedding and event decoration. Open your city page and book online.',
                'meta_keywords' => 'wedding decoration service areas, wedding decorator list, uttar pradesh wedding decoration',
                'heading' => 'Our Wedding & Event Decoration <span>Service Areas</span>',
                'intro_content' => 'Choose your state, district and city to read full details about wedding decoration, haldi, mehendi, balloon, flower and complete event decoration services available in your area.',
            ],
            'contact-us' => [
                'meta_title' => 'Contact Us | Anuj Kumar Wedding Event Planners',
                'meta_description' => 'Contact Anuj Kumar Wedding Event Planners for wedding, haldi, mehendi, birthday and event decoration anywhere in Uttar Pradesh. Quick enquiry and free quotation.',
                'meta_keywords' => 'contact wedding decorator, wedding decoration booking, event decoration enquiry',
                'heading' => 'Contact Your Trusted <span>Wedding & Event Decoration Team</span>',
                'intro_content' => 'Planning a wedding, Haldi, Mehendi, birthday, ceremony or special event? Tell Anuj Kumar Wedding Event Planners about your occasion and let us help you plan a decoration setup in Agra, Aligarh, Bulandshahr, Gautam Buddh Nagar, Ghaziabad, Hapur, Meerut and Mathura.',
                'content' => $this->contactContent(),
            ],
            'sitemap' => [
                'meta_title' => 'Sitemap | Anuj Kumar Wedding Event Planners',
                'meta_description' => 'Complete list of pages on our website — services, packages, blogs, service areas and important information.',
                'heading' => 'Explore Our Website <span>Wedding & Event Decoration Services</span>',
                'intro_content' => 'Explore all important pages, wedding decoration services, event decoration solutions, packages, gallery, blogs, frequently asked questions and contact information from Anuj Kumar Wedding Event Planners.',
            ],
            'privacy-policy' => [
                'meta_title' => 'Privacy Policy | Anuj Kumar Wedding Event Planners',
                'meta_description' => 'Read how Anuj Kumar Wedding Event Planners collects, uses and protects the information you share through our website and enquiry forms.',
                'heading' => 'Privacy Policy <span>Your Privacy Matters to Us</span>',
                'intro_content' => 'Learn how Anuj Kumar Wedding Event Planners collects, uses and protects information shared through our website, enquiry forms and communication channels.',
            ],
            'terms-and-conditions' => [
                'meta_title' => 'Terms & Conditions | Anuj Kumar Wedding Event Planners',
                'meta_description' => 'Terms and conditions for using our website and booking our wedding and event decoration services in Uttar Pradesh.',
                'heading' => 'Terms & Conditions <span>For Using Our Website & Services</span>',
                'intro_content' => 'Please read these terms and conditions carefully before using the Anuj Kumar Wedding Event Planners website, exploring our decoration services or submitting an event enquiry.',
            ],
            'disclaimer' => [
                'meta_title' => 'Disclaimer | Anuj Kumar Wedding Event Planners',
                'meta_description' => 'Disclaimer for the Anuj Kumar Wedding Event Planners website, covering content, pricing, images and external links.',
                'heading' => 'Website Disclaimer <span>Important Information About Our Website</span>',
                'intro_content' => 'Please read this disclaimer carefully to understand the nature of the information published on the Anuj Kumar Wedding Event Planners website and how website content should be understood.',
            ],
            'wedding-event-decoration-guide' => [
                'meta_title' => 'Wedding & Event Decoration Guide | Ideas, Themes & Planning Tips',
                'meta_description' => 'A complete guide to wedding and event decoration — choosing themes, colours, flowers, lighting and budgets, and picking the right decoration team.',
                'meta_keywords' => 'wedding decoration guide, event decoration ideas, wedding theme guide, decoration planning tips',
                'heading' => 'Complete Wedding & Event <span>Decoration Planning Guide</span>',
                'intro_content' => 'Planning a wedding, Haldi, Mehendi, birthday or special celebration? Explore practical decoration ideas, theme planning tips, venue styling guidance and event decoration services to create a beautiful celebration that feels truly yours.',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function homeContent(): array
    {
        return [
            'hero_slides' => [
                [
                    'image' => 'assets/banner/wedding-decoration.png',
                    'tag' => 'Wedding & Event Decoration',
                    'heading' => 'Beautiful Celebrations, <span>Beautifully Decorated</span>',
                    'description' => 'Thoughtfully designed wedding and event decoration that transforms every celebration into a memorable experience.',
                    'primary_label' => 'Explore Services',
                    'primary_url' => '#services',
                    'highlights' => ['Elegant Concepts', 'Custom Decoration', 'Complete Execution'],
                ],
                [
                    'image' => 'assets/services/comp-event.png',
                    'tag' => 'Stage & Backdrop Decoration',
                    'heading' => 'Make Your Wedding <span>Stage Shine</span>',
                    'description' => 'Create a stunning focal point with beautifully styled stages, backdrops, lighting and decoration details designed around your celebration.',
                    'primary_label' => 'View Stage Designs',
                    'primary_url' => '#services',
                    'highlights' => ['Premium Backdrops', 'Designer Lighting', 'Personalised Themes'],
                ],
                [
                    'image' => 'assets/banner/haldi-decoration.png',
                    'tag' => 'Haldi & Mehendi Decoration',
                    'heading' => 'Colourful Traditions, <span>Beautifully Styled</span>',
                    'description' => 'Vibrant haldi and mehendi setups with fresh flowers, marigold garlands, props and comfortable seating for the whole family.',
                    'primary_label' => 'See Haldi & Mehendi Ideas',
                    'primary_url' => '#gallery',
                    'highlights' => ['Fresh Flowers', 'Traditional Props', 'Photo-Ready Setups'],
                ],
                [
                    'image' => 'assets/services/entry-gate-decoration.png',
                    'tag' => 'Entry Gate & Counters',
                    'heading' => 'Grand Entrances, <span>Memorable Moments</span>',
                    'description' => 'Impressive entry gates, welcome counters and stylish food counters that set the tone for your entire celebration.',
                    'primary_label' => 'Explore Entry Decoration',
                    'primary_url' => '#services',
                    'highlights' => ['Grand Entry Gates', 'Themed Counters', 'Fresh Flower Work'],
                ],
                [
                    'image' => 'assets/services/comp-event.png',
                    'tag' => 'Complete Event Solutions',
                    'heading' => 'From Concept To <span>Celebration</span>',
                    'description' => 'One team for the complete function — decoration, lighting, stage, flowers, balloons and on-time execution.',
                    'primary_label' => 'Plan Your Event',
                    'primary_url' => '#contact',
                    'highlights' => ['Single Point of Contact', 'On-Time Setup', 'Transparent Pricing'],
                ],
            ],
            'trust_items' => [
                ['icon' => 'fa-regular fa-lightbulb', 'label' => 'Creative', 'title' => 'Designs'],
                ['icon' => 'fa-solid fa-palette', 'label' => 'Fully', 'title' => 'Customized Themes'],
                ['icon' => 'fa-regular fa-clock', 'label' => 'Reliable', 'title' => 'On-Time Setup'],
                ['icon' => 'fa-solid fa-gem', 'label' => 'Finest', 'title' => 'Premium Quality'],
                ['icon' => 'fa-solid fa-heart', 'label' => 'From Concept To Celebration', 'title' => 'Complete Event Solutions'],
            ],
            'about' => [
                'eyebrow' => 'Your Celebration Partner',
                'heading' => 'Turning Your Dreams Into <em>Beautiful Celebrations</em>',
                'lead' => 'Anuj Kumar Wedding Event Planners creates thoughtfully designed wedding and event decorations that look beautiful, feel personal and make every celebration memorable.',
                'text' => 'From wedding functions and haldi ceremonies to mehendi, chunni ceremonies, birthdays, anniversaries, balloon decoration, fogg & matka entries and stylish counters, our team creates complete decoration solutions according to your event and vision.',
                'location' => 'Sikandrabad, Bulandshahr',
                'cta_label' => 'Plan Your Celebration',
            ],
            'services_heading' => [
                'eyebrow' => 'What We Do',
                'heading' => 'Our Wedding & Event <em>Decoration Services</em>',
                'text' => 'From intimate family functions to grand wedding celebrations, we create thoughtfully styled decoration that brings your vision beautifully to life.',
            ],
            'services_cta' => [
                'eyebrow' => "Let's Create Something Beautiful",
                'heading' => 'Turn Your Next Event Into A <em>Grand Celebration</em>',
                'text' => "Tell us about your occasion, theme and requirements. We'll create a decoration plan that feels uniquely yours and beautifully reflects your celebration.",
                'button' => 'Get Your Custom Quote',
                'occasions' => [
                    ['icon' => 'fa-solid fa-ring', 'label' => 'Weddings'],
                    ['icon' => 'fa-solid fa-cake-candles', 'label' => 'Birthdays'],
                    ['icon' => 'fa-solid fa-heart', 'label' => 'Engagements'],
                    ['icon' => 'fa-solid fa-baby', 'label' => 'Baby Showers'],
                    ['icon' => 'fa-solid fa-champagne-glasses', 'label' => 'Anniversaries'],
                    ['icon' => 'fa-solid fa-building', 'label' => 'Corporate Events'],
                ],
            ],
            'gallery_heading' => [
                'eyebrow' => 'Our Recent Work',
                'heading' => 'Wedding & Event <em>Decoration Gallery</em>',
                'text' => 'Explore beautifully styled wedding stages, haldi and mehendi setups, birthdays, flower decoration and special celebration decoration.',
            ],
            'process' => [
                'eyebrow' => 'Simple Process',
                'heading' => 'How We Make Your Celebration Special',
                'text' => 'Easy planning from the first conversation to the final decoration setup.',
                'steps' => [
                    ['icon' => 'fa-regular fa-comments', 'title' => 'Share Your Vision', 'text' => 'Tell us about your event, venue, date and decoration requirements.'],
                    ['icon' => 'fa-solid fa-palette', 'title' => 'Choose Your Theme', 'text' => 'Select from our ideas or create a customized theme for your event.'],
                    ['icon' => 'fa-solid fa-screwdriver-wrench', 'title' => 'We Design & Setup', 'text' => 'Our team handles the decoration setup with attention to every detail.'],
                    ['icon' => 'fa-solid fa-wand-magic-sparkles', 'title' => 'Enjoy Your Celebration', 'text' => 'Relax, celebrate and create beautiful memories with your loved ones.'],
                ],
            ],
            'packages' => [
                'eyebrow' => 'Flexible Plans',
                'heading' => 'Event Decoration Packages',
                'text' => 'Choose a suitable decoration style and customize it according to your event.',
                'items' => [
                    [
                        'title' => 'Basic Celebration',
                        'label' => 'Essential Decoration',
                        'image' => 'assets/packages/basic.png',
                        'featured' => false,
                        'features' => ['Essential decoration setup', 'Elegant theme options', 'Ideal for small functions', 'Professional setup team'],
                    ],
                    [
                        'title' => 'Premium Celebration',
                        'label' => 'Customized Decoration',
                        'image' => 'assets/packages/premium.png',
                        'featured' => true,
                        'badge' => 'Most Popular',
                        'features' => ['Customized decoration concept', 'Premium props & setups', 'Ideal for family functions', 'Dedicated setup team'],
                    ],
                    [
                        'title' => 'Royal Celebration',
                        'label' => 'Luxury Event Styling',
                        'image' => 'assets/packages/royal.png',
                        'featured' => false,
                        'features' => ['Exclusive theme designs', 'Luxury decoration elements', 'Wedding-ready styling', 'Complete event decoration'],
                    ],
                ],
            ],
            'blogs' => [
                'eyebrow' => 'Tips & Inspiration',
                'heading' => 'Latest From Our Blog',
                'text' => 'Useful decoration ideas and wedding inspiration for your next celebration.',
                'items' => [
                    ['category' => 'Balloon Decoration', 'title' => 'Top Balloon Decoration Ideas for Birthday Parties', 'excerpt' => 'Discover stylish balloon arches, garlands and backdrop ideas for a memorable birthday celebration.', 'image' => 'assets/blogs/03.png', 'url' => '#'],
                    ['category' => 'Haldi Decoration', 'title' => 'Haldi Decoration Themes That Make Your Function Special', 'excerpt' => 'Explore vibrant haldi decoration ideas using flowers, traditional elements, seating and colorful backdrops.', 'image' => 'assets/blogs/01.png', 'url' => '#'],
                    ['category' => 'Mehendi Decoration', 'title' => 'How to Plan a Beautiful Mehendi Night Decoration', 'excerpt' => 'Learn how to choose colours, florals, seating, props and a theme for a memorable mehendi night.', 'image' => 'assets/blogs/02.png', 'url' => '#'],
                ],
            ],
            'contact' => [
                'eyebrow' => "Let's Plan Your Celebration",
                'heading' => 'Ready to Book Your <em>Decoration?</em>',
                'text' => 'Share your date, city, venue and budget with our team. We will send design options, photos and a final quotation the same day.',
                'highlights' => ['Quick Enquiry', 'Customized Decoration', 'Coverage Across Uttar Pradesh'],
            ],
            'hero_highlights' => ['Wedding & Event Specialists', 'Custom Themes', 'On-Time Setup', 'Across Uttar Pradesh'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function contactContent(): array
    {
        return [
            'eyebrow' => "Let's Plan Your Celebration",
            'highlights' => ['Quick Enquiry', 'Customized Decoration', 'Coverage Across Uttar Pradesh'],
            'phone_note' => 'Discuss your event, venue and decoration requirements directly with our team.',
            'whatsapp_note' => 'Share your event date, venue and decoration requirements with us on WhatsApp.',
            'hours' => 'Open all days, 9:00 AM to 8:00 PM',
        ];
    }

    /**
     * @return list<array{image: string, title: string, category: string}>
     */
    private function galleryItems(string $category, int $limit = 9): array
    {
        $labels = [
            'Wedding Decor',
            'Event Decor',
            'Haldi Decor',
            'Mehendi Decor',
            'Balloon Decor',
            'Birthday Decor',
            'Flower Decor',
            'Stage Decor',
            'Counter Decor',
            'Entry Decor',
            'Chunni Ceremony',
            'Complete Event Decor',
        ];

        return collect(MediaLibrary::images($category))
            ->take($limit)
            ->values()
            ->map(fn (string $image, int $index): array => [
                'image' => $image,
                'title' => $labels[$index % count($labels)],
                'category' => $labels[$index % count($labels)],
            ])
            ->all();
    }

    private function uttarPradeshIntro(): string
    {
        return '<p>Anuj Kumar Wedding Event Planners is a full-service wedding and event decoration team working across Uttar Pradesh. From a small haldi function at home to a grand wedding stage, we plan, design and execute every detail.</p>'
            .'<p>We accept bookings in Agra, Aligarh, Bulandshahr, Gautam Buddh Nagar (Noida and Greater Noida), Ghaziabad, Hapur, Meerut and Mathura. Every function gets its own custom theme, fresh flowers, balloons, lighting, entry decoration, counters and stage backdrop.</p>';
    }

    private function uttarPradeshAbout(): string
    {
        return '<p>Our team designs every function around a single theme — colour palette, flowers, lighting, drapes and props all matched together, so that everything looks perfect in the photographs. We first understand the size and lighting of the venue, and then finalise the design.</p>'
            .'<p>To book, just share your date, city and budget — we send sample designs, photos and a final quotation within 24 hours. Advance booking locks in your setup date.</p>';
    }

    /**
     * @return list<array{question: string, answer: string}>
     */
    private function uttarPradeshFaqs(): array
    {
        return [
            [
                'question' => 'Do you provide wedding decoration in every city of Uttar Pradesh?',
                'answer' => 'We currently accept wedding and event decoration bookings in Agra, Aligarh, Bulandshahr, Gautam Buddh Nagar (Noida and Greater Noida), Ghaziabad, Hapur, Meerut and Mathura.',
            ],
            [
                'question' => 'What is the decoration charge in Uttar Pradesh?',
                'answer' => 'Charges depend on the size of the setup, flowers, lighting and theme. A package is available for every budget, from a small home function to a grand banquet stage. Call +91 73020 15048 for an exact quotation.',
            ],
            [
                'question' => 'Which services do you offer?',
                'answer' => 'Wedding decoration, haldi decoration, mehendi decoration, chunni ceremony, balloon decoration, birthday decoration, flower decoration, stage and backdrop, entry gate decoration, wedding counters, fogg and matka entry and complete event decoration.',
            ],
            [
                'question' => 'Can I customise the theme and colour?',
                'answer' => 'Yes. The theme, colour combination, flowers, balloons, lighting, props and backdrop are all customised as per your preference.',
            ],
            [
                'question' => 'How can I book a decoration date?',
                'answer' => 'Share your date, city, venue and budget — we send design options and a quotation. Once you confirm in advance, your date is locked in. Call or WhatsApp +91 73020 15048.',
            ],
        ];
    }

    /**
     * @return list<array{question: string, answer: string}>
     */
    private function homeFaqs(): array
    {
        return [
            [
                'question' => 'Do you provide wedding decoration across Uttar Pradesh?',
                'answer' => 'We currently accept wedding and event decoration bookings in Agra, Aligarh, Bulandshahr, Gautam Buddh Nagar (Noida and Greater Noida), Ghaziabad, Hapur, Meerut and Mathura.',
            ],
            [
                'question' => 'What is the price of wedding decoration?',
                'answer' => 'The price depends on the size of the setup, flowers, lighting and theme. We have packages for every budget. Call or WhatsApp +91 73020 15048 for an exact quotation.',
            ],
            [
                'question' => 'Do you take same-day decoration bookings?',
                'answer' => 'Yes, subject to availability. If you confirm in the morning, a same-day or next-day setup can be arranged for most functions.',
            ],
            [
                'question' => 'Can I customise the theme and colours?',
                'answer' => 'Absolutely. The theme, colours, flowers, balloons, drapes, lighting and props are all decided as per your preference and venue.',
            ],
            [
                'question' => 'How do I book a date?',
                'answer' => 'Share your date, city, venue and budget on WhatsApp. We send design photos and a quotation the same day. Once you confirm, your date is blocked.',
            ],
        ];
    }

    /**
     * Category-wise FAQs shown on the FAQ page.
     *
     * @return list<array{question: string, answer: string, category: string}>
     */
    private function faqPageFaqs(): array
    {
        return [
            [
                'category' => 'wedding',
                'question' => 'Do you provide wedding decoration services?',
                'answer' => 'Yes. We provide wedding decoration solutions including stages, backdrops, entrances, flower decoration, wedding counters and complete event decoration according to the event requirements.',
            ],
            [
                'category' => 'wedding',
                'question' => 'Can wedding decoration be customized?',
                'answer' => 'Yes. Wedding decoration can be planned around your preferred colours, theme, venue layout, event type and specific decoration requirements.',
            ],
            [
                'category' => 'wedding',
                'question' => 'Do you provide flower decoration for weddings?',
                'answer' => 'Yes. Flower decoration can be planned for wedding stages, entrances, backdrops, venues and selected celebration spaces.',
            ],
            [
                'category' => 'wedding',
                'question' => 'Do you provide complete wedding event decoration?',
                'answer' => 'Yes. Complete event decoration can include multiple decoration elements planned together according to your wedding venue, functions and overall requirements.',
            ],

            [
                'category' => 'function',
                'question' => 'Do you provide Haldi decoration?',
                'answer' => 'Yes. We provide colourful Haldi decoration setups designed around the function, venue, preferred colours and overall celebration style.',
            ],
            [
                'category' => 'function',
                'question' => 'Do you provide Mehendi decoration?',
                'answer' => 'Yes. Mehendi decoration can include colourful themed styling, decorative backdrops and arrangements suited to the celebration space.',
            ],
            [
                'category' => 'function',
                'question' => 'Do you provide Chunni Ceremony decoration?',
                'answer' => 'Yes. We provide graceful decoration arrangements for Chunni Ceremony and traditional wedding functions according to the venue and requirements.',
            ],
            [
                'category' => 'function',
                'question' => 'Can multiple wedding functions be decorated?',
                'answer' => 'Yes. Multiple functions can be planned separately or as part of a broader decoration requirement, depending on your event schedule and venue arrangements.',
            ],

            [
                'category' => 'event',
                'question' => 'Do you provide balloon decoration for birthdays?',
                'answer' => 'Yes. We create balloon decoration setups for birthdays, anniversaries, family celebrations and other special occasions.',
            ],
            [
                'category' => 'event',
                'question' => 'Can balloon decoration be customized?',
                'answer' => 'Yes. Balloon decoration can be customized according to your preferred colours, celebration theme, venue layout and event requirements.',
            ],
            [
                'category' => 'event',
                'question' => 'Do you decorate parties and family events?',
                'answer' => 'Yes. We provide decoration solutions for parties, birthdays, anniversaries, family functions and other special gatherings.',
            ],
            [
                'category' => 'event',
                'question' => 'Do you provide complete event decoration?',
                'answer' => 'Yes. Complete event decoration can be planned around the venue, event type, decoration requirements and overall visual theme.',
            ],

            [
                'category' => 'booking',
                'question' => 'Which areas do you serve?',
                'answer' => 'We currently accept wedding and event decoration bookings in Agra, Aligarh, Bulandshahr, Gautam Buddh Nagar (Noida and Greater Noida), Ghaziabad, Hapur, Meerut and Mathura. Open the Locations page to find your city.',
            ],
            [
                'category' => 'booking',
                'question' => 'How early should I contact you?',
                'answer' => 'It is recommended to contact the team as early as possible with your event date, venue and decoration requirements so that the decoration plan can be discussed according to availability.',
            ],
            [
                'category' => 'booking',
                'question' => 'What information should I share for an enquiry?',
                'answer' => 'You can share your event date, event type, venue or address, preferred decoration service, approximate requirements and any specific theme or colour preferences.',
            ],
            [
                'category' => 'booking',
                'question' => 'How can I contact your team?',
                'answer' => 'You can contact our team through the Contact Us page or call and WhatsApp our team directly, then share your event requirements.',
            ],
        ];
    }
}
