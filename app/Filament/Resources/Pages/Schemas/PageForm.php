<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Forms\Components\MediaUpload;
use App\Filament\Schemas\SeoFields;
use App\Models\PageBlock;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

/**
 * Page add/edit form.
 *
 * The form is grouped in the same order as the website so an admin can see
 * exactly which section of the page they are changing:
 *
 * 1. Basics  -> URL
 * 2. Visibility
 * 3. Top / hero text
 * 4. Google SEO
 * 5. Images (upload from computer)
 * 6. Home page sections (home only)
 * 7. Extra content sections (any page, shown above the footer)
 * 8. FAQs
 */
class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('1. Page Basics')
                    ->description('The page name and its URL. The URL is created automatically from the name.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Page Name')
                            ->placeholder('About Us')
                            ->required()
                            ->maxLength(120)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->placeholder('about-us')
                            ->helperText('Use "home" for the home page. Other pages: yoursite.com/about-us')
                            ->required()
                            ->maxLength(160)
                            ->unique(ignoreRecord: true),

                        Select::make('template')
                            ->label('Page Template')
                            ->helperText('The designed layout used to render this page.')
                            ->options([
                                'default' => 'Default page',
                                'home' => 'Home page',
                                'about' => 'About page',
                                'contact' => 'Contact page',
                                'gallery' => 'Gallery page',
                                'packages' => 'Packages listing',
                                'blogs' => 'Blog listing',
                                'faq' => 'FAQ page',
                                'legal' => 'Legal / prose page',
                                'guide' => 'Guide / long-form page',
                                'services' => 'Services listing',
                                'locations' => 'Locations listing',
                                'sitemap' => 'Sitemap page',
                            ])
                            ->default('default')
                            ->required(),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->helperText('A smaller number appears first.')
                            ->numeric()
                            ->default(0),

                        TextInput::make('priority')
                            ->label('Sitemap Priority (1-100)')
                            ->helperText('100 = the most important page.')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(50),
                    ])
                    ->columns(2),

                Section::make('2. Show / Hide This Page')
                    ->description('OFF = visitors get a 404, Google is told noindex, and the page is removed from the sitemap.')
                    ->schema([
                        Toggle::make('is_visible')
                            ->label('Show this page on the website')
                            ->helperText('ON = visible to visitors and indexed by Google. OFF = hidden everywhere.')
                            ->default(true)
                            ->inline(false),
                    ]),

                Section::make('3. Top / Hero Text')
                    ->description('The first text visitors see at the top of this page. Empty fields render nothing - no placeholder marketing copy is added.')
                    ->schema([
                        TextInput::make('eyebrow')
                            ->label('Small Label Above Heading')
                            ->maxLength(120)
                            ->columnSpanFull(),

                        TextInput::make('heading')
                            ->label('Main Heading (H1)')
                            ->helperText('You can use <span>...</span> to highlight part of the heading.')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('sub_heading')
                            ->label('Sub Heading')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('intro_content')
                            ->label('Intro Paragraph')
                            ->helperText('HTML is allowed (for example <p>...</p>).')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                SeoFields::section('4. Google Search (SEO)'),
                SeoFields::socialSection(),

                Section::make('5. Images')
                    ->description('Upload images from your computer. Empty = the website uses its default image. You can replace or delete any uploaded image here.')
                    ->schema([
                        MediaUpload::make('image')
                            ->label('Main / Hero Image')
                            ->helperText('Used at the top of this page, in social previews and as the fallback for other images.')
                            ->image()
                            ->disk('public')
                            ->directory('pages')
                            ->visibility('public')
                            ->fetchFileInformation(false)
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        MediaUpload::make('og_image')
                            ->label('Social Share Image (WhatsApp / Facebook)')
                            ->helperText('Optional. Falls back to the main image.')
                            ->image()
                            ->disk('public')
                            ->directory('pages/og')
                            ->visibility('public')
                            ->fetchFileInformation(false)
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        Repeater::make('gallery')
                            ->label('Gallery Images')
                            ->helperText('These images appear in the gallery grid on this page. Add, edit, reorder or delete any item.')
                            ->schema([
                                MediaUpload::make('image')
                                    ->label('Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/gallery')
                                    ->visibility('public')
                                    ->fetchFileInformation(false)
                                    ->imageEditor()
                                    ->maxSize(5120)
                                    ->required(),

                                TextInput::make('title')
                                    ->label('Title / Alt Text')
                                    ->helperText('Describes the image for Google and screen readers.')
                                    ->maxLength(255),

                                TextInput::make('category')
                                    ->label('Category')
                                    ->maxLength(120),

                                Toggle::make('is_visible')
                                    ->label('Show this image')
                                    ->helperText('OFF = hidden from the website but kept here.')
                                    ->default(true),
                            ])
                            ->columns(3)
                            ->addActionLabel('Add gallery image')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? $state['image'] ?? null)
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),

                Section::make('6. Home Page Sections')
                    ->description('Hero slides, statistics and testimonials are edited here. Package and blog cards come from their own admin sections so listings stay in sync.')
                    ->visible(fn (Get $get): bool => $get('slug') === 'home')
                    ->schema([
                        Repeater::make('content.hero_slides')
                            ->label('Hero Slides')
                            ->helperText('These are the homepage banners, shown in this order. Drag to reorder, add or delete a slide, and turn a slide off to hide it without deleting it.')
                            ->schema([
                                MediaUpload::make('image')
                                    ->label('Background Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('home/hero')
                                    ->visibility('public')
                                    ->fetchFileInformation(false)
                                    ->imageEditor()
                                    ->maxSize(5120)
                                    ->required(),
                                TextInput::make('tag')->label('Small Tag')->maxLength(120),
                                TextInput::make('heading')->label('Heading (HTML allowed)')->maxLength(255),
                                Textarea::make('description')->label('Description')->rows(2),
                                TextInput::make('primary_label')->label('Button Label')->maxLength(80),
                                TextInput::make('primary_url')->label('Button URL')->maxLength(255),
                                TagsInput::make('highlights')->label('Tick Points'),
                                Toggle::make('is_active')
                                    ->label('Show this slide')
                                    ->helperText('OFF = hidden from the homepage slider but kept here.')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['tag'] ?? null)
                            ->addActionLabel('Add hero slide')
                            ->defaultItems(0),

                        Repeater::make('content.stats')
                            ->label('Statistics')
                            ->schema([
                                TextInput::make('icon')->label('Font Awesome Icon')->maxLength(120),
                                TextInput::make('value')->label('Value')->maxLength(30),
                                TextInput::make('label')->label('Label')->maxLength(80),
                            ])
                            ->columns(3)
                            ->collapsible()
                            ->addActionLabel('Add statistic')
                            ->defaultItems(0),

                        Placeholder::make('packages_source')
                            ->label('Packages')
                            ->content('Home page package cards are loaded from the Packages admin section. Edit, reorder or publish them there so the listing page and home page always stay in sync.'),

                        Repeater::make('content.testimonials.items')
                            ->label('Testimonials')
                            ->schema([
                                Textarea::make('quote')->label('Quote')->rows(2),
                                TextInput::make('name')->label('Client Name')->maxLength(120),
                                TextInput::make('event')->label('Event Type')->maxLength(120),
                                MediaUpload::make('image')
                                    ->label('Client Photo')
                                    ->image()
                                    ->disk('public')
                                    ->directory('home/testimonials')
                                    ->visibility('public')
                                    ->fetchFileInformation(false)
                                    ->imageEditor()
                                    ->maxSize(2048),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->addActionLabel('Add testimonial')
                            ->defaultItems(0),

                        Placeholder::make('blogs_source')
                            ->label('Blog Cards')
                            ->content('Home page blog cards are loaded from the Posts admin section. Publish, edit or reorder posts there so the blog listing and home page always stay in sync.'),
                    ])
                    ->collapsed(),

                Section::make('7. Extra Content Sections (Any Page)')
                    ->description('Add any section you want on this page: heading, text, image and button. These sections appear just above the footer. You can add, edit, reorder or delete them at any time.')
                    ->schema([
                        RichEditor::make('content.body')
                            ->label('Main Body Content')
                            ->helperText('Used by the generic "Default page" template as the main article/body text. Leave empty to hide it.')
                            ->visible(fn (Get $get): bool => in_array($get('template'), ['default', 'legal'], true))
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('pages/body')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),

                        RichEditor::make('content.about_story')
                            ->label('About Us Story')
                            ->helperText('Shown in the About Us story block. Use {address} to insert the business address from your profile.')
                            ->visible(fn (Get $get): bool => $get('slug') === 'about-us')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('pages/about')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),

                        Toggle::make('content.replace_template')
                            ->label('Hide the built-in design and show only my content sections')
                            ->helperText('OFF = the designed page stays and your sections are added above the footer. ON = the designed page content is hidden completely and only the sections below are shown (header and footer stay). Add your sections first, then turn this ON.')
                            ->default(false)
                            ->inline(false)
                            ->columnSpanFull(),

                        Repeater::make('content.sections')
                            ->label('Content Sections')
                            ->schema([
                                TextInput::make('eyebrow')
                                    ->label('Small Label (optional)')
                                    ->maxLength(120),

                                TextInput::make('heading')
                                    ->label('Section Heading')
                                    ->maxLength(255),

                                RichEditor::make('body')
                                    ->label('Section Text')
                                    ->helperText('Use the toolbar for bold, lists, links and headings.')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('pages/content')
                                    ->fileAttachmentsVisibility('public')
                                    ->columnSpanFull(),

                                MediaUpload::make('image')
                                    ->label('Section Image (optional)')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/sections')
                                    ->visibility('public')
                                    ->fetchFileInformation(false)
                                    ->imageEditor()
                                    ->maxSize(5120),

                                Select::make('image_position')
                                    ->label('Image Position')
                                    ->options([
                                        'right' => 'Image on the right',
                                        'left' => 'Image on the left',
                                        'none' => 'No image',
                                    ])
                                    ->default('right'),

                                TextInput::make('button_label')
                                    ->label('Button Text (optional)')
                                    ->maxLength(80),

                                TextInput::make('button_url')
                                    ->label('Button Link (optional)')
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['heading'] ?? $state['eyebrow'] ?? null)
                            ->addActionLabel('Add content section')
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),

                Section::make('8. Built-in Design Text')
                    ->description('These are the built-in headings, paragraphs and labels in the designed layout. Edit a value to change the website. Leave a value empty to hide that text. The keys are reference names - do not rename them.')
                    ->schema([
                        KeyValue::make('content.copy')
                            ->label('Text Values')
                            ->keyLabel('Text Key')
                            ->valueLabel('Website Text')
                            ->addActionLabel('Add text')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                Section::make('9. Flexible Content Blocks')
                    ->description('Advanced: add a block, give it a key, and the page template can render it by key. Blocks can be reordered, hidden or deleted.')
                    ->schema([
                        Repeater::make('blocks')
                            ->relationship()
                            ->label('Page Blocks')
                            ->schema([
                                TextInput::make('key')
                                    ->label('Block Key')
                                    ->helperText('Example: about.story, gallery.intro')
                                    ->required()
                                    ->maxLength(120),

                                Select::make('type')
                                    ->label('Block Type')
                                    ->options(PageBlock::TYPES)
                                    ->default('text')
                                    ->required(),

                                TextInput::make('label')
                                    ->label('Admin Label')
                                    ->maxLength(160),

                                KeyValue::make('fields')
                                    ->label('Block Fields')
                                    ->helperText('Example: heading, text, image, button_label, button_url')
                                    ->columnSpanFull(),

                                Toggle::make('is_visible')
                                    ->label('Visible')
                                    ->default(true),

                                TextInput::make('sort_order')
                                    ->label('Sort Order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? $state['key'] ?? null)
                            ->addActionLabel('Add block')
                            ->defaultItems(0)
                            ->orderColumn('sort_order')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                Section::make('10. FAQs')
                    ->description('These questions appear on this page and create a Google FAQ rich result. Add, edit, reorder or delete any question.')
                    ->schema([
                        Repeater::make('faqs')
                            ->label('Questions & Answers')
                            ->schema([
                                TextInput::make('question')
                                    ->label('Question')
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('answer')
                                    ->label('Answer')
                                    ->required()
                                    ->rows(3),
                            ])
                            ->addActionLabel('Add FAQ')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

            ]);
    }
}
