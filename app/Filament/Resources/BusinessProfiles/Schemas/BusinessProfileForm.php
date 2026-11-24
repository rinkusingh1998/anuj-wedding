<?php

namespace App\Filament\Resources\BusinessProfiles\Schemas;

use App\Filament\Forms\Components\MediaUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BusinessProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | Business Information
                |--------------------------------------------------------------------------
                */

                Section::make('1. Business Information')
                    ->description('Basic information about your business')
                    ->schema([
                        TextInput::make('business_name')
                            ->label('Business Name')
                            ->placeholder('Anuj Kumar Wedding Event Planners')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('short_name')
                            ->label('Short Name')
                            ->placeholder('Anuj Kumar')
                            ->maxLength(100),

                        TextInput::make('tagline')
                            ->label('Tagline')
                            ->placeholder('Wedding & Event Decoration Specialists')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | Branding
                |--------------------------------------------------------------------------
                */

                Section::make('2. Branding (Logo & Favicon)')
                    ->description('Upload your logo, mobile logo and favicon from your computer. These images appear in the header, browser tab and social previews.')
                    ->schema([
                        MediaUpload::make('logo')
                            ->label('Main Logo')
                            ->image()
                            ->disk('public')
                            ->directory('business-profile')
                            ->imageEditor()
                            ->maxSize(2048),

                        MediaUpload::make('mobile_logo')
                            ->label('Mobile Logo')
                            ->image()
                            ->disk('public')
                            ->directory('business-profile')
                            ->imageEditor()
                            ->maxSize(2048),

                        MediaUpload::make('favicon')
                            ->label('Favicon')
                            ->image()
                            ->disk('public')
                            ->directory('business-profile')
                            ->maxSize(512),

                        MediaUpload::make('og_image')
                            ->label('Social Share Image (WhatsApp / Facebook)')
                            ->helperText('Optional. Used on Facebook, WhatsApp and Twitter previews.')
                            ->image()
                            ->disk('public')
                            ->directory('business-profile')
                            ->visibility('public')
                            ->fetchFileInformation(false)
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('3. Contact Information')
                    ->description('These details appear on every page, in the footer and in Google structured data.')
                    ->schema([
                        TextInput::make('phone')->label('Phone Number')->maxLength(255),
                        TextInput::make('whatsapp')->label('WhatsApp Number')->helperText('Digits only, with country code. Example: 917302015048')->maxLength(255),
                        TextInput::make('email')->label('Email')->email()->maxLength(255),
                        TextInput::make('google_map_url')->label('Google Map URL')->helperText('Paste the share/embed link of your Google Maps location.')->maxLength(2048),
                        Textarea::make('address')->label('Address')->helperText('Shown in the footer, contact page and Google structured data.')->rows(3)->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('4. Social Links')
                    ->schema([
                        TextInput::make('facebook_url')->label('Facebook URL')->maxLength(2048),
                        TextInput::make('instagram_url')->label('Instagram URL')->maxLength(2048),
                        TextInput::make('youtube_url')->label('YouTube URL')->maxLength(2048),
                    ])
                    ->columns(3),

                Section::make('5. Footer Text')
                    ->schema([
                        Textarea::make('footer_about')->label('Footer About Text')->rows(3)->columnSpanFull(),
                        TextInput::make('footer_copyright')->label('Copyright Text')->maxLength(255)->columnSpanFull(),
                    ]),

            ]);
    }
}
