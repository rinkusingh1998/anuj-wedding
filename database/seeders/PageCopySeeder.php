<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Seeds the built-in design text so every heading, paragraph and label in the
 * designed page templates can be edited from the admin panel.
 *
 * The values live in each page's `content.copy` array. Existing copy is never
 * overwritten, so admin edits (and deliberately deleted keys) are preserved.
 */
class PageCopySeeder extends Seeder
{
    public function run(): void
    {
        $copy = [
            'about-us' => [
                'copy.text_1' => 'Anuj Kumar Wedding Event Planners',
                'copy.text_2' => 'Years of Creative
                                Event Experience',
                'copy.text_3' => 'About Our Wedding & Event Decoration Company',
                'copy.text_4' => 'We Turn Celebration Spaces Into',
                'copy.text_5' => 'Theme ideas planned around your occasion.',
                'copy.text_6' => 'Decoration adapted to your venue and preferences.',
                'copy.text_7' => 'Planned setup with attention to timing.',
                'copy.text_8' => 'Every celebration gets individual attention.',
                'copy.text_9' => 'Discuss Your Event With Us',
                'copy.text_10' => 'Why Choose Anuj Kumar Wedding Event Planners',
                'copy.text_11' => 'A beautiful event starts with understanding the
                    occasion. We combine creative ideas, customized
                    styling and organized execution to create a
                    decoration experience that feels personal.',
                'copy.text_12' => 'Creative Decoration Ideas',
                'copy.text_13' => 'Attractive wedding and event decoration concepts
                            planned according to your occasion, venue
                            and celebration style.',
                'copy.text_14' => 'Colors, flowers, balloons, backdrops and
                            decorative elements can be styled around
                            your preferences.',
                'copy.text_15' => 'We focus on preparation and coordination
                            to help keep your decoration setup aligned
                            with the event schedule.',
                'copy.text_16' => 'Celebration-Focused Service',
                'copy.text_17' => 'Every wedding, ceremony and family event
                            is different, so we keep our service
                            personal and detail-focused.',
                'copy.text_18' => 'Our Approach To Wedding & Event Decoration',
                'copy.text_19' => 'Every Detail Matters
                        When You\'re Celebrating',
                'copy.text_20' => 'Our work is centered around creating attractive,
                        coordinated and celebration-ready spaces. We
                        pay attention to the visual details that help
                        transform an ordinary venue into a memorable
                        celebration space.',
                'copy.text_21' => 'Whether you are planning a traditional wedding,
                        Haldi, Mehendi, Chunni ceremony, birthday,
                        family function or complete event decoration,
                        we work to make the decoration complement
                        the occasion beautifully.',
                'copy.text_22' => 'Elegant Finishing Details',
                'copy.text_23' => 'Traditional & Modern Styles',
                'copy.text_24' => 'Decoration With
                            Character',
                'copy.text_25' => 'Designed to complement
                            your celebration.',
                'copy.text_26' => 'Wedding & Event Decoration Services',
                'copy.text_27' => 'Creative balloon arches, garlands,
                                themed backdrops and attractive party
                                decoration setups.',
                'copy.text_28' => 'Bright and colorful Haldi decoration
                                with vibrant themes and traditional
                                celebration styling.',
                'copy.text_29' => 'Beautiful Mehendi decoration with colorful
                                themes, florals, seating and decorative props.',
                'copy.text_30' => 'Chunni Ceremony Decoration',
                'copy.text_31' => 'Elegant traditional and contemporary
                                decoration for beautiful Chunni ceremony
                                celebrations.',
                'copy.text_32' => 'Special decorative elements and effects
                                designed to add character to celebrations.',
                'copy.text_33' => 'Stylish welcome, food, dessert and
                                themed counters for wedding events.',
                'copy.text_34' => 'Floral decoration for stages, entrances,
                                backdrops, tables and wedding venues.',
                'copy.text_35' => 'Complete event styling from decoration concepts
                                and backdrops to finishing details.',
                'copy.text_36' => 'Looking for a customized decoration setup?',
                'copy.text_37' => 'Our Wedding & Event Decoration Process',
                'copy.text_38' => 'We follow a simple and organized approach to understand
                    your requirements, plan the decoration and prepare a
                    celebration-ready setup.',
                'copy.text_39' => 'We discuss your occasion, venue, theme,
                        preferences and decoration requirements.',
                'copy.text_40' => 'We organize decoration elements, layouts, colors,
                        props and the overall visual direction.',
                'copy.text_41' => 'The planned concept is transformed into a
                        coordinated and attractive event setup.',
                'copy.text_42' => 'You enjoy your special occasion while the
                        decoration becomes part of the celebration.',
                'copy.text_43' => 'Plan Your Celebration With Us',
                'copy.text_44' => 'Let\'s Create A
                        Celebration Worth Remembering',
                'copy.text_45' => 'Tell us about your wedding, Haldi, Mehendi,
                        birthday, ceremony or special event. Share your
                        venue, date and decoration requirements and
                        let\'s discuss the right decoration concept for you.',
            ],
            'blogs' => [
                'copy.text_1' => 'Wedding & Event Decoration Journal',
                'copy.text_2' => 'Start With Our Latest Wedding Ideas',
                'copy.text_3' => 'Get practical inspiration for wedding decoration,
                    function decoration and event planning before you start
                    designing your celebration.',
                'copy.text_4' => 'How to Choose the Right Wedding Decoration
                        for Your Celebration',
                'copy.text_5' => 'Learn how to choose a wedding decoration style according
                        to your venue, function, theme, colours and overall
                        celebration requirements.',
                'copy.text_6' => 'Explore decoration inspiration, planning tips and
                        function-specific ideas for your next celebration.',
                'copy.text_7' => 'Elegant Wedding Decoration Ideas
                                for a Memorable Celebration',
                'copy.text_8' => 'Discover elegant decoration concepts that can
                                transform your wedding venue into a beautiful
                                celebration space.',
                'copy.text_9' => 'Beautiful Haldi Decoration Ideas
                                for a Colourful Function',
                'copy.text_10' => 'From floral backdrops to vibrant seating,
                                explore ideas for creating a memorable
                                Haldi celebration.',
                'copy.text_11' => 'Flower Decoration Ideas for
                                Weddings & Functions',
                'copy.text_12' => 'Explore floral decoration styles that bring
                                elegance, freshness and a beautiful visual
                                identity to celebrations.',
                'copy.text_13' => 'Mehendi Decoration Ideas
                                for a Stylish Celebration',
                'copy.text_14' => 'Create a vibrant Mehendi setup with colourful
                                decoration, floral details and beautiful guest areas.',
                'copy.text_15' => 'Complete Event Decoration Ideas
                                for Special Occasions',
                'copy.text_16' => 'Plan a complete event setup with coordinated
                                decoration, entrance styling, stage design and
                                guest-focused details.',
                'copy.text_17' => 'Wedding Decoration Planning:
                                What You Should Decide First',
                'copy.text_18' => 'Understand the important decoration decisions
                                to make before your wedding decoration
                                planning begins.',
                'copy.text_19' => 'Find Inspiration for Every Celebration',
                'copy.text_20' => 'Browse ideas according to your wedding function,
                    decoration requirement or event planning need.',
                'copy.text_21' => 'Elegant wedding decoration ideas, themes,
                            backdrops and venue styling inspiration.',
                'copy.text_22' => 'Colourful function decoration ideas for
                            Haldi and Mehendi celebrations.',
                'copy.text_23' => 'Balloon decoration, birthdays and
                            creative event styling inspiration.',
                'copy.text_24' => 'Useful planning guidance for choosing
                            decoration, venues and event requirements.',
                'copy.text_25' => 'Let\'s Create Something Beautiful',
                'copy.text_26' => 'Have a Celebration in Mind?
                        Let\'s Plan Your Decoration.',
                'copy.text_27' => 'Tell us about your wedding, function or event
                        and our team can help you plan a beautiful
                        decoration setup according to your requirements.',
            ],
            'contact-us' => [
                'copy.text_1' => 'Let\'s Plan Your Celebration',
                'copy.text_2' => 'Discuss your event, venue and decoration
                            requirements directly with our team.',
                'copy.text_3' => 'Share your event date, venue and decoration
                            requirements with us on WhatsApp.',
                'copy.text_4' => 'Every event has different requirements.
                            Share a few details about your celebration
                            and our team can understand what kind of
                            decoration setup you are looking for.',
                'copy.text_5' => 'Whether you need Haldi decoration, Mehendi
                            decoration, Flower Decoration, Balloon
                            Decoration, Wedding Counters or Complete
                            Event Decoration, start your enquiry here.',
                'copy.text_6' => 'Date, time, venue and event type.',
                'copy.text_7' => 'Tell us the service you need.',
                'copy.text_8' => 'Discuss Your Requirements',
                'copy.text_9' => 'We can discuss the right decoration direction.',
                'copy.text_10' => 'Fill in your details and tell us what
                                you are planning.',
                'copy.text_11' => 'Chunni Ceremony Decoration',
                'copy.text_12' => 'What Can We Decorate For You?',
                'copy.text_13' => 'Explore individual services before sending your
                    enquiry and find the decoration style that best
                    matches your celebration.',
                'copy.text_14' => 'Creative party & event styling',
                'copy.text_15' => 'Bright & colorful ceremony decoration',
                'copy.text_16' => 'Vibrant wedding ceremony styling',
                'copy.text_17' => 'Traditional & elegant decoration',
                'copy.text_18' => 'Special event effects & decoration',
                'copy.text_19' => 'Floral stages & venue styling',
                'copy.text_20' => 'Complete celebration styling',
                'copy.text_21' => 'Let\'s Make Your Celebration
                        Look Extraordinary',
                'copy.text_22' => 'Share your event details with Anuj Kumar Wedding
                        Event Planners and let\'s discuss a decoration
                        concept that suits your occasion and venue.',
            ],
            'disclaimer' => [
                'copy.text_1' => 'Website Information Disclaimer',
                'copy.text_2' => 'Transparent Communication',
                'copy.text_3' => 'The information published on this website is intended
                        to help visitors understand our wedding and event
                        decoration services, explore decoration ideas and
                        contact our team regarding their event requirements.',
                'copy.text_4' => 'While we aim to keep website information useful and
                        accurate, actual event arrangements may vary depending
                        on venue conditions, event requirements, availability,
                        materials and other circumstances.',
                'copy.text_5' => 'Website Information May Vary',
                'copy.text_6' => 'Final service details should always be confirmed
                            directly with our team.',
                'copy.text_7' => 'The content available on this website is
                                provided for general informational purposes.
                                It is intended to give visitors an overview
                                of our wedding and event decoration services,
                                planning information and available contact
                                options.',
                'copy.text_8' => 'Website content should not be considered a
                                final quotation, confirmed booking or binding
                                service agreement unless specifically confirmed
                                by our team.',
                'copy.text_9' => 'Decoration Images & Examples',
                'copy.text_10' => 'Images and visual examples displayed on the
                                website may be used to provide inspiration
                                and demonstrate possible decoration styles,
                                themes, layouts and event setups.',
                'copy.text_11' => 'The final appearance of an event decoration
                                may differ from website images because of
                                venue size, lighting, materials, availability,
                                theme preferences and other event-specific
                                factors.',
                'copy.text_12' => 'The availability of any wedding or event
                                decoration service depends on the requested
                                event date, location, event requirements and
                                our team\'s availability.',
                'copy.text_13' => 'Services may vary according to event
                                    requirements.',
                'copy.text_14' => 'Availability should be confirmed before
                                    making event arrangements.',
                'copy.text_15' => 'Decoration concepts may be customized
                                    according to the event.',
                'copy.text_16' => 'Venue conditions may affect the final
                                    decoration setup.',
                'copy.text_17' => 'Prices for wedding and event decoration can
                                vary according to event type, venue, decoration
                                requirements, design, materials, scale and
                                other factors.',
                'copy.text_18' => 'Any pricing information discussed through the
                                website or during an initial enquiry should be
                                treated as subject to confirmation based on
                                the specific event requirements.',
                'copy.text_19' => 'No Guarantee of Availability',
                'copy.text_20' => 'Displaying a service, decoration style or
                                package on this website does not guarantee that
                                the same service, design, material or setup
                                will be available for every event.',
                'copy.text_21' => 'Visitors should contact our team to confirm
                                current availability and event-specific
                                requirements.',
                'copy.text_22' => 'External Links & Third-Party Services',
                'copy.text_23' => 'This website may contain links to external
                                websites, social media platforms, maps,
                                communication services or other third-party
                                resources.',
                'copy.text_24' => 'These external services operate independently
                                and may have their own terms, privacy policies
                                and practices. We are not responsible for the
                                content or policies of third-party websites.',
                'copy.text_25' => 'Accuracy of Website Content',
                'copy.text_26' => 'We make reasonable efforts to keep the
                                information on this website useful and
                                up-to-date. However, errors, omissions or
                                changes may occur from time to time.',
                'copy.text_27' => 'Service details, descriptions, images and other
                                website information may be updated without
                                prior notice.',
                'copy.text_28' => 'Event-Specific Confirmation',
                'copy.text_29' => 'Because every celebration is different, final
                                event details should be discussed and confirmed
                                directly with our team before making decisions
                                based on information available on the website.',
                'copy.text_30' => 'This may include decoration design, venue
                                requirements, event timing, materials,
                                availability and other arrangements.',
                'copy.text_31' => 'We aim to keep the website accessible and
                                functional, but continuous or uninterrupted
                                availability cannot be guaranteed.',
                'copy.text_32' => 'Website functionality may occasionally be
                                affected by maintenance, technical issues,
                                hosting problems or circumstances outside our
                                reasonable control.',
                'copy.text_33' => 'If you need clarification about any service,
                                decoration concept, availability or information
                                displayed on this website, please contact our
                                team directly before making your event plans.',
                'copy.text_34' => 'Review the main points about website information,
                            service availability and event-specific
                            confirmation.',
                'copy.text_35' => 'Get event-specific information directly from our
                            wedding and event decoration team.',
                'copy.text_36' => 'Explore our decoration services and find the right
                    decoration solution for your wedding, function or event.',
                'copy.text_37' => 'Anuj Kumar Wedding Event Planners',
                'copy.text_38' => 'Contact our team to discuss your wedding, Haldi,
                        Mehendi, birthday, party or complete event decoration
                        requirements and get event-specific information.',
            ],
            'faq' => [
                'copy.text_1' => 'Wedding & Event Decoration FAQs',
                'copy.text_2' => 'Wedding stages, entrances & decoration',
                'copy.text_3' => 'Haldi, Mehendi & ceremonies',
                'copy.text_4' => 'Birthdays, parties & celebrations',
                'copy.text_5' => 'Booking, location & requirements',
                'copy.text_6' => 'Everything You Need To Know',
                'copy.text_7' => 'Planning a wedding or special event involves many
                            small decisions. Our FAQ section answers common
                            questions about decoration services, customization,
                            occasions, locations and enquiries.',
                'copy.text_8' => 'If you have a specific requirement that is not
                            covered below, you can contact our team directly
                            and discuss your event date, venue and preferred
                            decoration style.',
                'copy.text_9' => 'Customized decoration planning',
                'copy.text_10' => 'Wedding & event decoration',
                'copy.text_11' => 'Service available in nearby areas',
                'copy.text_12' => 'Talk To Our Decoration Team',
                'copy.text_13' => 'Frequently Asked Questions',
                'copy.text_14' => 'Common questions about wedding decoration, stages,
                    entrances, backdrops and complete wedding decoration.',
                'copy.text_15' => 'Haldi, Mehendi & Ceremony',
                'copy.text_16' => 'Learn more about decoration for Haldi, Mehendi, Chunni
                    Ceremony and other traditional wedding functions.',
                'copy.text_17' => 'Birthday & Event Decoration',
                'copy.text_18' => 'Common questions about balloon decoration, birthdays,
                    anniversaries, parties and special events.',
                'copy.text_19' => 'Important information about event dates, customization,
                    service areas and getting in touch with our team.',
                'copy.text_20' => 'Explore our individual decoration services to find the
                    right option for your wedding, function, party or special
                    celebration.',
                'copy.text_21' => 'Colourful and custom balloon decoration for birthdays,
                            weddings and celebrations.',
                'copy.text_22' => 'Colourful Haldi decoration for vibrant
                            pre-wedding celebrations.',
                'copy.text_23' => 'Stylish Mehendi decoration with colourful and
                            elegant themed styling.',
                'copy.text_24' => 'Elegant floral decoration for stages, entrances,
                            backdrops and venues.',
                'copy.text_25' => 'If you could not find the answer you were looking for,
                        share your event date, venue and decoration
                        requirements with our team.',
            ],
            'gallery' => [
                'copy.text_1' => 'Wedding & Event Decoration Gallery',
                'copy.text_2' => 'Beautiful Event Decorations',
                'copy.text_3' => 'Every celebration has its own character. Our decoration
                    work combines elegant styling, thoughtful details and
                    professional execution to create event spaces that feel
                    special from every angle.',
                'copy.text_4' => 'Browse selected decoration inspirations for weddings,
                    haldi, mehendi, parties and special events.',
                'copy.text_5' => 'A Decoration Style of Its Own',
                'copy.text_6' => 'Whether you want a traditional wedding setup, a
                        colourful haldi arrangement, elegant floral decoration,
                        beautiful balloon decoration or complete event styling,
                        we can plan the look around your celebration.',
                'copy.text_7' => 'Choose a decoration style that matches your function,
                    venue and celebration theme.',
                'copy.text_8' => 'LET\'S CREATE SOMETHING BEAUTIFUL',
                'copy.text_9' => 'Share your event date, venue and decoration
                        requirements with us. Our team will help you plan
                        a beautiful setup suited to your celebration.',
            ],
            'home' => [
                'copy.text_1' => 'Frequently Asked Questions',
            ],
            'packages' => [
                'copy.text_1' => 'Wedding & Event Decoration Packages',
                'copy.text_2' => 'FIND YOUR PERFECT PACKAGE',
                'copy.text_3' => 'Decoration Options Designed',
                'copy.text_4' => 'Whether you are planning an intimate function or a
                    complete wedding celebration, our decoration packages
                    provide a convenient starting point for creating a
                    beautiful and coordinated event setup.',
                'copy.text_5' => 'LOOKING FOR SOMETHING DIFFERENT?',
                'copy.text_6' => 'Create a Custom Decoration',
                'copy.text_7' => 'Every celebration is different. Share your event
                        type, date, venue and decoration ideas with us and
                        our team can help you plan a setup around your
                        specific requirements.',
                'copy.text_8' => 'Discuss Your Requirements',
                'copy.text_9' => 'Decoration elements are planned to work together
                        for a coordinated visual appearance.',
                'copy.text_10' => 'Package elements can be discussed and customized
                        according to your celebration requirements.',
                'copy.text_11' => 'Share your date, venue and requirements so the
                        decoration can be planned accordingly.',
                'copy.text_12' => 'LET\'S PLAN YOUR CELEBRATION',
                'copy.text_13' => 'Starts With the Right Decoration',
                'copy.text_14' => 'Tell Anuj Kumar Wedding Event Planners about your event,
                    preferred package and decoration requirements. We\'ll help
                    you plan a beautiful setup for your special occasion.',
            ],
            'privacy-policy' => [
                'copy.text_1' => 'Website Privacy & Data Protection',
                'copy.text_2' => 'At Anuj Kumar Wedding Event Planners, we respect the
                        privacy of visitors and customers who use our website
                        to explore wedding and event decoration services or
                        contact our team.',
                'copy.text_3' => 'This Privacy Policy explains the general way in which
                        information submitted through our website may be
                        handled and used to respond to enquiries and provide
                        relevant services.',
                'copy.text_4' => 'We aim to handle information responsibly and only
                            use it for legitimate business and communication
                            purposes.',
                'copy.text_5' => 'Information We May Collect',
                'copy.text_6' => 'When you use our website or contact us regarding
                                wedding and event decoration services, you may
                                voluntarily provide information such as your
                                name, mobile number, event type, event date,
                                event time, venue or address and additional
                                requirements.',
                'copy.text_7' => 'We may also receive information that you choose
                                to share with us when communicating through
                                enquiry forms, phone calls, WhatsApp or other
                                available communication channels.',
                'copy.text_8' => 'How We Use Your Information',
                'copy.text_9' => 'Information submitted through the website may
                                be used to understand your event requirements,
                                respond to your enquiry and provide information
                                about suitable wedding or event decoration
                                services.',
                'copy.text_10' => 'Respond to decoration enquiries.',
                'copy.text_11' => 'Understand your event requirements.',
                'copy.text_12' => 'Discuss event date, venue and decoration needs.',
                'copy.text_13' => 'Provide relevant service information.',
                'copy.text_14' => 'Enquiry & Contact Information',
                'copy.text_15' => 'If you submit an enquiry through our website,
                                the information you provide may be used by our
                                team to contact you regarding your requested
                                wedding or event decoration service.',
                'copy.text_16' => 'Please provide accurate information so that our
                                team can understand your requirements and
                                communicate with you effectively.',
                'copy.text_17' => 'WhatsApp & External Communication',
                'copy.text_18' => 'Our website may provide links that allow you to
                                contact us through WhatsApp or other external
                                communication platforms. When you use an
                                external platform, its own privacy policy and
                                terms may also apply.',
                'copy.text_19' => 'We recommend reviewing the privacy practices of
                                any third-party platform before sharing personal
                                information through that platform.',
                'copy.text_20' => 'We take reasonable steps to protect information
                                shared with us from unauthorized access,
                                misuse or disclosure. However, no website or
                                internet transmission can be guaranteed to be
                                completely secure.',
                'copy.text_21' => 'Our website may contain links to external
                                websites or services. These websites operate
                                independently and may have their own privacy
                                policies, terms and data practices.',
                'copy.text_22' => 'We are not responsible for the privacy practices
                                or content of third-party websites.',
                'copy.text_23' => 'Our website or its supporting technologies may
                                use cookies or similar technologies to improve
                                website functionality, understand website usage
                                and provide a better browsing experience.',
                'copy.text_24' => 'You can manage cookie preferences through your
                                browser settings where supported.',
                'copy.text_25' => 'You may choose what information you provide when
                                contacting us. If you do not wish to provide
                                certain information, you may still browse the
                                publicly available sections of our website,
                                although some enquiry or communication features
                                may require relevant details.',
                'copy.text_26' => 'Changes to This Privacy Policy',
                'copy.text_27' => 'We may update this Privacy Policy from time to
                                time to reflect changes in our website,
                                services or business practices. Any updated
                                version will be published on this page.',
                'copy.text_28' => 'If you have any questions about this Privacy
                                Policy or how information is handled, you can
                                contact Anuj Kumar Wedding Event Planners
                                directly.',
                'copy.text_29' => 'Please read this policy to understand how
                            information submitted through our website may be
                            handled.',
                'copy.text_30' => 'Talk to our team about wedding and event
                            decoration requirements.',
                'copy.text_31' => 'Explore our wedding and event decoration services or get
                    in touch with our team to discuss your celebration.',
                'copy.text_32' => 'Anuj Kumar Wedding Event Planners',
                'copy.text_33' => 'Explore our decoration services or contact our team
                        to discuss your wedding, Haldi, Mehendi, birthday,
                        party or complete event decoration requirements.',
            ],
            'services' => [
                'copy.text_1' => 'Wedding & Event Decoration Services',
                'copy.text_2' => 'Everything You Need For A',
                'copy.text_3' => 'Beautifully Decorated Celebration',
                'copy.text_4' => 'From intimate family functions to complete wedding
                    celebrations, we provide professional decoration solutions
                    with attention to theme, colour, venue and every important
                    visual detail.',
                'copy.text_5' => 'WEDDING & EVENT DECORATION',
                'copy.text_6' => 'Select a service to explore its decoration possibilities,
                    styling ideas and event-specific solutions.',
                'copy.text_7' => 'Complete Celebration Experience',
                'copy.text_8' => 'Our approach is focused on creating a coordinated
                        visual experience. From colours and flowers to
                        backdrops, counters and finishing details, every
                        element is planned to work beautifully together.',
                'copy.text_9' => 'Custom decoration requirements',
                'copy.text_10' => 'Tell us about your event, date, venue and decoration
                        requirements. We will help you plan a beautiful setup
                        for your special occasion.',
            ],
            'sitemap' => [
                'copy.text_1' => 'Our sitemap makes it easy to discover every important
                        section of the website. Browse our wedding and event
                        decoration services, explore our work, read helpful
                        decoration ideas and get in touch with our team.',
                'copy.text_2' => 'Whether you are planning a wedding, Haldi, Mehendi,
                        Chunni ceremony, birthday party or a complete event,
                        you can quickly find the right information below.',
                'copy.text_3' => 'Simple Website Navigation',
                'copy.text_4' => 'Explore services, pages, resources and
                            event planning information.',
                'copy.text_5' => 'Quickly access our main pages, decoration services,
                    resources and useful information.',
                'copy.text_6' => 'Wedding & Event Decoration Services',
                'copy.text_7' => 'Stylish balloon decoration for birthdays,
                                parties and special celebrations.',
                'copy.text_8' => 'Colourful and vibrant decoration ideas for
                                beautiful Haldi ceremonies.',
                'copy.text_9' => 'Elegant Mehendi setups designed around your
                                celebration theme and style.',
                'copy.text_10' => 'Chunni Ceremony Decoration',
                'copy.text_11' => 'Beautiful traditional decoration for memorable
                                Chunni ceremony celebrations.',
                'copy.text_12' => 'Creative traditional event styling with
                                Fogg & Matka decoration elements.',
                'copy.text_13' => 'Attractive and functional counters for
                                weddings and special events.',
                'copy.text_14' => 'Elegant floral decoration for stages,
                                entrances, venues and ceremonies.',
                'copy.text_15' => 'Complete decoration solutions for weddings,
                                functions, parties and special occasions.',
                'copy.text_16' => 'Planning & Helpful Resources',
                'copy.text_17' => 'Discover inspiration and practical ideas for
                                planning beautiful celebrations.',
                'copy.text_18' => 'Frequently Asked Questions',
                'copy.text_19' => 'Get quick answers about decoration services,
                                planning and enquiries.',
                'copy.text_20' => 'View decoration inspiration for weddings,
                                functions and celebrations.',
                'copy.text_21' => 'Explore event decoration packages and
                                choose a suitable celebration setup.',
                'copy.text_22' => 'Legal & Website Information',
                'copy.text_23' => 'Planning Something Special?',
                'copy.text_24' => 'Let\'s Create a Celebration',
                'copy.text_25' => 'Explore our services or contact our team to discuss
                        your wedding, Haldi, Mehendi, birthday, party or
                        complete event decoration requirements.',
            ],
            'terms-and-conditions' => [
                'copy.text_1' => 'Website Terms & Conditions',
                'copy.text_2' => 'These Terms & Conditions describe the general terms
                        applicable to visitors and users of the Anuj Kumar
                        Wedding Event Planners website.',
                'copy.text_3' => 'By accessing or using this website, you acknowledge
                        that you have read and understood these terms and agree
                        to use the website responsibly.',
                'copy.text_4' => 'Website information is provided to help visitors
                            understand our services and make enquiries.',
                'copy.text_5' => 'By accessing and using this website, you agree
                                to comply with these Terms & Conditions and
                                applicable laws. If you do not agree with any
                                part of these terms, please discontinue use of
                                the website.',
                'copy.text_6' => 'We aim to provide useful and accurate
                                information about our wedding and event
                                decoration services. However, service
                                availability, decoration designs, materials,
                                arrangements and other event details may vary
                                depending on individual requirements.',
                'copy.text_7' => 'Website content may be updated from time to
                                time without prior notice.',
                'copy.text_8' => 'Information submitted through our enquiry form
                                is used to understand your event requirements
                                and to communicate with you regarding the
                                requested decoration service.',
                'copy.text_9' => 'Submitting an enquiry does not automatically
                                constitute a confirmed booking or guarantee
                                availability for a particular event date.',
                'copy.text_10' => 'Event Requirements & Customization',
                'copy.text_11' => 'Wedding and event decoration requirements can
                                differ based on venue, event size, decoration
                                style, theme, materials, setup requirements and
                                other factors.',
                'copy.text_12' => 'Decoration concepts may be customized
                                    according to event requirements.',
                'copy.text_13' => 'Final arrangements depend on the agreed
                                    event requirements.',
                'copy.text_14' => 'Availability may vary according to the
                                    requested event date.',
                'copy.text_15' => 'Specific designs may depend on available
                                    materials and venue conditions.',
                'copy.text_16' => 'Decoration requirements can vary significantly
                                from one event to another. Any pricing,
                                quotation or estimate provided during an
                                enquiry should be understood in relation to
                                the specific event requirements discussed with
                                our team.',
                'copy.text_17' => 'A quotation or discussion does not itself
                                represent a confirmed booking unless the
                                booking has been mutually confirmed.',
                'copy.text_18' => 'Event bookings are subject to availability and
                                confirmation. Details such as event date,
                                venue, decoration requirements and agreed
                                arrangements may need to be finalized before
                                an event is confirmed.',
                'copy.text_19' => 'Visitors should contact our team directly for
                                the latest information regarding availability
                                and booking requirements.',
                'copy.text_20' => 'Website text, graphics, photographs, layouts,
                                branding and other content may be used for
                                informational and promotional purposes.',
                'copy.text_21' => 'You should not reproduce, copy, modify,
                                republish or commercially use website content
                                without appropriate authorization.',
                'copy.text_22' => 'Our website may contain links to third-party
                                websites or communication platforms. These
                                services operate independently and may have
                                their own terms, conditions and privacy
                                policies.',
                'copy.text_23' => 'We are not responsible for the content,
                                availability or policies of external websites.',
                'copy.text_24' => 'Responsible Use of the Website',
                'copy.text_25' => 'Visitors are expected to use this website for
                                lawful and legitimate purposes. You should not
                                knowingly attempt to interfere with website
                                functionality, misuse enquiry forms or engage
                                in activities that could harm the website or
                                its users.',
                'copy.text_26' => 'Limitation of Information',
                'copy.text_27' => 'Website information is intended for general
                                informational purposes. Event decoration
                                outcomes, availability, designs and
                                arrangements may depend on individual event
                                circumstances and requirements.',
                'copy.text_28' => 'These Terms & Conditions may be updated from
                                time to time to reflect changes in our website,
                                services or business practices.',
                'copy.text_29' => 'The latest version published on this page will
                                apply to continued use of the website.',
                'copy.text_30' => 'If you have questions regarding these Terms &
                                Conditions or our wedding and event decoration
                                services, please contact our team directly.',
                'copy.text_31' => 'Use these links to quickly move through the main
                            sections of our website terms.',
                'copy.text_32' => 'Contact our team to discuss your wedding or event
                            decoration requirements.',
                'copy.text_33' => 'Explore our services, FAQs and privacy information before
                    getting in touch with our team.',
                'copy.text_34' => 'Explore our wedding and event decoration services.',
                'copy.text_35' => 'Explore decoration packages for different events.',
                'copy.text_36' => 'Frequently Asked Questions',
                'copy.text_37' => 'Find answers to common decoration questions.',
                'copy.text_38' => 'Learn how website information may be handled.',
                'copy.text_39' => 'Anuj Kumar Wedding Event Planners',
                'copy.text_40' => 'Explore our decoration services or contact our team
                        to discuss your wedding, Haldi, Mehendi, birthday,
                        party or complete event decoration requirements.',
            ],
            'wedding-event-decoration-guide' => [
                'copy.text_1' => 'Wedding & Event Decoration Planning Guide',
                'copy.text_2' => 'Before You Start Decorating',
                'copy.text_3' => 'Great Decoration Starts With',
                'copy.text_4' => 'Beautiful event decoration is not only about flowers,
                        lights or backdrops. The right decoration depends on
                        the event type, venue, guest experience, colour palette,
                        theme, available space and overall celebration style.',
                'copy.text_5' => 'This guide brings the important planning decisions
                        together in one place so you can understand what to
                        consider before finalizing your wedding or event decoration.',
                'copy.text_6' => 'Decide your event style and venue requirements
                            before selecting individual decoration elements.',
                'copy.text_7' => 'Choose the type of celebration you are planning and
                    explore the most relevant decoration options.',
                'copy.text_8' => 'Explore Our Decoration Services',
                'copy.text_9' => 'Explore individual decoration services and discover which
                    options can work best for your celebration.',
                'copy.text_10' => 'Creative balloon styling for birthdays, parties and
                        colourful celebrations.',
                'copy.text_11' => 'Bright and traditional Haldi decoration designed
                        around your celebration style.',
                'copy.text_12' => 'Stylish Mehendi setups with floral, colourful and
                        traditional decorative elements.',
                'copy.text_13' => 'Chunni Ceremony Decoration',
                'copy.text_14' => 'Elegant traditional decoration for meaningful family
                        ceremonies and special occasions.',
                'copy.text_15' => 'Traditional and creative decorative elements for
                        functions and celebration setups.',
                'copy.text_16' => 'Attractive counters designed to complement weddings
                        and special event experiences.',
                'copy.text_17' => 'Floral decoration for wedding stages, entrances,
                        backdrops, venues and ceremonies.',
                'copy.text_18' => 'Complete decoration solutions for weddings, functions,
                        parties and special celebrations.',
                'copy.text_19' => 'Planning these basic details early can make the
                        decoration discussion much easier and help create a
                        setup that matches your event.',
                'copy.text_20' => 'Smart Decoration Planning',
                'copy.text_21' => 'How To Choose The Right Decoration',
                'copy.text_22' => 'The best decoration is the one that fits your event,
                    venue, personality and overall celebration experience.',
                'copy.text_23' => 'Understand the size, entrance, stage area, ceiling,
                        available walls and guest movement before selecting
                        large decoration elements.',
                'copy.text_24' => 'Choose a small group of complementary colours instead
                        of mixing too many unrelated decoration elements.',
                'copy.text_25' => 'Your stage, backdrop or main celebration area should
                        create a strong visual point for guests and photographs.',
                'copy.text_26' => 'Sharing your event date and requirements early gives
                        more time to discuss the decoration direction and
                        availability.',
                'copy.text_27' => 'Continue exploring our website for decoration ideas,
                    packages, FAQs and examples.',
                'copy.text_28' => 'Explore visual decoration inspiration.',
                'copy.text_29' => 'Explore packages for different celebrations.',
                'copy.text_30' => 'Read more planning and decoration ideas.',
                'copy.text_31' => 'Find answers to common event questions.',
                'copy.text_32' => 'Your Celebration Starts Here',
                'copy.text_33' => 'Have Your Decoration Plan?',
                'copy.text_34' => 'Share your event type, date, venue and decoration
                        requirements with our team and let\'s explore the
                        right setup for your celebration.',
            ],
        ];

        foreach ($copy as $slug => $values) {
            $page = Page::query()->where('slug', $slug)->first();

            if (! $page) {
                continue;
            }

            $content = $page->content ?? [];
            $existing = $content['copy'] ?? null;

            // The copy map uses "copy.text_1" keys. Inside the page's copy
            // array the prefix is not repeated: content.copy.text_1.
            $normalized = [];

            foreach ($values as $key => $value) {
                $normalized[str_starts_with($key, 'copy.') ? substr($key, 5) : $key] = $value;
            }

            if (is_array($existing)) {
                $fixed = [];
                $changed = false;

                // Repair keys written by an earlier build that stored the
                // prefix inside the copy array as well.
                foreach ($existing as $key => $value) {
                    if (str_starts_with($key, 'copy.')) {
                        $key = substr($key, 5);
                        $changed = true;
                    }

                    $fixed[$key] = $value;
                }

                if ($changed) {
                    $content['copy'] = $fixed;
                    $page->content = $content;
                    $page->save();
                }

                continue;
            }

            $content['copy'] = $normalized;

            $page->content = $content;
            $page->save();
        }
    }
}
