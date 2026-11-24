<x-filament-panels::page>

    <div class="grid gap-6">

        <x-filament::section>
            <x-slot name="heading">How this admin panel works</x-slot>

            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <p>
                    Every page of the website is controlled from this admin panel. Choose the section below,
                    open the record and change the field you need. Click <strong>Save</strong> and the website
                    updates immediately.
                </p>

                <p>
                    Images: use the upload box to choose a photo from your computer. Click the
                    <strong>X</strong> on an image to remove it. For lists (gallery, FAQs, packages, sections),
                    use <strong>Add</strong> to create a new item, the <strong>trash icon</strong> to delete an
                    item, and drag the handle to reorder.
                </p>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Pages (Home, About, Services, Gallery, Packages, Blogs, FAQ, Contact, Legal)</x-slot>

            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <p>
                    Go to <strong>Pages</strong> and open the page you want to change. The form is numbered in
                    the same order as the website:
                </p>

                <ul class="list-disc space-y-2 pl-5">
                    <li><strong>1. Page Basics</strong> - page name, URL and sitemap order.</li>
                    <li><strong>2. Show / Hide</strong> - hide a page from visitors and Google.</li>
                    <li><strong>3. Top / Hero Text</strong> - the first heading and paragraph at the top of the page.</li>
                    <li><strong>4. Google Search (SEO)</strong> - the title, description and keywords shown in Google.</li>
                    <li><strong>5. Images</strong> - main image, WhatsApp/Facebook share image and the gallery. Gallery images fill the photo blocks on that page.</li>
                    <li><strong>6. Home Page Sections</strong> - only for the home page: hero slides, statistics, packages, testimonials and blog cards. Add, edit, delete or reorder anything.</li>
                    <li><strong>7. Extra Content Sections</strong> - add any new section (heading, text, image, button) to this page. It appears just above the footer. Delete it any time.</li>
                    <li><strong>8. FAQs</strong> - questions shown on the page and in Google's FAQ result.</li>
                </ul>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Sidebar map (where everything lives)</x-slot>

            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <p>
                    The sidebar is grouped by the part of the website you are editing:
                </p>

                <ul class="list-disc space-y-2 pl-5">
                    <li><strong>Content</strong> - Pages, Packages, FAQs, Testimonials and the Media Library.</li>
                    <li><strong>Services</strong> - Service Categories (grouping) and Services.</li>
                    <li><strong>Locations</strong> - States, Cities &amp; Villages, Localities &amp; Areas.</li>
                    <li><strong>Blog</strong> - Blog Categories and Blog Posts.</li>
                    <li><strong>Leads</strong> - Enquiries submitted from the website.</li>
                    <li><strong>SEO</strong> - SEO &amp; Sitemap settings and Redirects.</li>
                    <li><strong>Settings</strong> - Business Profile, Navigation Menus, Global Settings and Users.</li>
                    <li><strong>Help</strong> - this guide.</li>
                </ul>

                <p>
                    The Dashboard shows four cards (new enquiries, pages, services, media files). Click any card
                    to jump straight to that screen.
                </p>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Services (service + city SEO pages)</x-slot>

            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <p>
                    Go to <strong>Services</strong>. Every service automatically creates
                    <em>"service in city"</em> pages for every active city.
                </p>

                <p>
                    The services list is grouped by <strong>Service Categories</strong> (Wedding Functions,
                    Decoration &amp; Styling, Party &amp; Celebration), so you immediately see which service belongs
                    where. Open <strong>Services &rarr; Service Categories</strong> and use the
                    <strong>Services in this category</strong> table to add, remove or open a service.
                </p>

                <ul class="list-disc space-y-2 pl-5">
                    <li><strong>1. Service Basics</strong> - category, name, URL, icon and tagline.</li>
                    <li><strong>2. Show / Hide</strong> - hide the service everywhere.</li>
                    <li><strong>3. Page Content</strong> - short and long description used in the service text.</li>
                    <li><strong>4. Google Search (SEO)</strong> - meta title and description. Empty = automatic city-wise SEO text.</li>
                    <li><strong>5. Images</strong> - main image, share image and gallery photos of this service.</li>
                    <li><strong>6. Extra Content Sections</strong> - add, edit or delete extra content on the service page.</li>
                </ul>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Locations (State and City pages)</x-slot>

            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <p>
                    Go to <strong>States</strong> or <strong>Cities</strong>. Each state and city has its own SEO
                    landing page. You can change its heading, intro, about text, FAQs, images, gallery and
                    visibility. A city page also creates service pages for that city.
                </p>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Business Profile (phone, address, logo, social links)</x-slot>

            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <p>
                    Go to <strong>Business Profiles</strong> to change the details used across the whole website:
                    business name, phone, WhatsApp, email, address, Google Map link, logo, mobile logo, favicon,
                    social share image, Facebook/Instagram/YouTube links and footer text.
                </p>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">SEO &amp; Sitemap</x-slot>

            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <p>
                    Go to <strong>SEO &amp; Sitemap</strong> for global settings: site title, default description,
                    WhatsApp number, Google Analytics ID, sitemap regeneration and city import.
                </p>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Good to know</x-slot>

            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <ul class="list-disc space-y-2 pl-5">
                    <li>The designed layout stays the same. Use <strong>Extra Content Sections</strong> to add or remove content.</li>
                    <li>To replace a page's built-in design completely, add your own content sections and turn ON <strong>Hide the built-in design and show only my content sections</strong>.</li>
                    <li>Leave an SEO field empty and the website writes a sensible title and description automatically.</li>
                    <li>If you upload a new image, it replaces the old image after saving.</li>
                    <li>Turning <strong>Show</strong> off hides the page from visitors and removes it from the sitemap.</li>
                </ul>
            </div>
        </x-filament::section>

    </div>

</x-filament-panels::page>
