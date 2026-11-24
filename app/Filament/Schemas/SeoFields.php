<?php

namespace App\Filament\Schemas;

use App\Filament\Forms\Components\MediaUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

/**
 * Shared SEO form section.
 *
 * Every public content type (page, state, city, locality, service, package,
 * blog post) has the same SEO columns, so the admin panel uses this one
 * component instead of duplicating the same fields in every resource.
 */
final class SeoFields
{
    public static function section(string $label = 'Search Engine Optimisation (SEO)'): Section
    {
        return Section::make($label)
            ->description('Every field is optional. Empty values fall back to the record content and then to the global website settings. Canonical URLs and robots tags are generated automatically.')
            ->schema([
                TextInput::make('meta_title')
                    ->label('SEO Title')
                    ->helperText('60-65 characters is best. Empty = generated from the record name.')
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->helperText('150-160 characters is best. Empty = the record intro/sub heading.')
                    ->rows(2)
                    ->maxLength(320)
                    ->columnSpanFull(),

                Textarea::make('meta_keywords')
                    ->label('Meta Keywords')
                    ->rows(2)
                    ->columnSpanFull(),

                TextInput::make('canonical_url')
                    ->label('Canonical URL')
                    ->helperText('Leave empty to use this record\'s own URL. Only set this for duplicate content.')
                    ->url()
                    ->maxLength(500)
                    ->columnSpanFull(),

                Toggle::make('robots_index')
                    ->label('Allow search engines to index this page')
                    ->default(true),

                Toggle::make('robots_follow')
                    ->label('Tell search engines to follow links')
                    ->default(true),

                TextInput::make('focus_keyword')
                    ->label('Focus Keyword')
                    ->helperText('Used as an editorial reminder in the SEO checklist.')
                    ->maxLength(120),

                DateTimePicker::make('published_at')
                    ->label('Publish Date & Time')
                    ->helperText('Leave empty to publish immediately. A future date keeps the page hidden until then.')
                    ->seconds(false),

                TextInput::make('image_alt')
                    ->label('Main Image Alt Text')
                    ->helperText('Describe the image for Google and screen readers.')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->collapsed();
    }

    public static function socialSection(): Section
    {
        return Section::make('Social Sharing (Open Graph / Twitter)')
            ->description('Controls the preview shown when this page is shared on WhatsApp, Facebook, LinkedIn or X. Empty values fall back to the SEO title, description and image.')
            ->schema([
                TextInput::make('og_title')
                    ->label('Open Graph Title')
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('og_description')
                    ->label('Open Graph Description')
                    ->rows(2)
                    ->maxLength(320)
                    ->columnSpanFull(),

                Select::make('og_type')
                    ->label('Open Graph Type')
                    ->options([
                        'website' => 'Website',
                        'article' => 'Article',
                        'product' => 'Product',
                        'profile' => 'Profile',
                    ])
                    ->default('website'),

                MediaUpload::make('og_image')
                    ->label('Open Graph Image (1200 x 630 recommended)')
                    ->image()
                    ->disk('public')
                    ->directory('seo/og')
                    ->visibility('public')
                    ->fetchFileInformation(false)
                    ->imageEditor()
                    ->maxSize(5120),

                Select::make('twitter_card')
                    ->label('Twitter/X Card Type')
                    ->options([
                        'summary' => 'Summary',
                        'summary_large_image' => 'Summary with large image',
                    ])
                    ->default('summary_large_image'),

                TextInput::make('twitter_title')
                    ->label('Twitter/X Title')
                    ->maxLength(255),

                Textarea::make('twitter_description')
                    ->label('Twitter/X Description')
                    ->rows(2)
                    ->maxLength(320),

                MediaUpload::make('twitter_image')
                    ->label('Twitter/X Image')
                    ->image()
                    ->disk('public')
                    ->directory('seo/twitter')
                    ->visibility('public')
                    ->fetchFileInformation(false)
                    ->imageEditor()
                    ->maxSize(5120),
            ])
            ->columns(2)
            ->collapsed();
    }
}
