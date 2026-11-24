<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Filament\Forms\Components\MediaUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Testimonial')
                    ->description('Only publish real client feedback. No ratings are stored, so no fake review schema is generated.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Client Name')
                            ->required()
                            ->maxLength(140),

                        TextInput::make('event')
                            ->label('Event Type')
                            ->maxLength(140),

                        TextInput::make('city')
                            ->label('City')
                            ->maxLength(140),

                        Textarea::make('quote')
                            ->label('Testimonial')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        MediaUpload::make('image')
                            ->label('Client Photo')
                            ->image()
                            ->disk('public')
                            ->directory('testimonials')
                            ->visibility('public')
                            ->fetchFileInformation(false)
                            ->imageEditor()
                            ->maxSize(2048),

                        Toggle::make('is_visible')
                            ->label('Show on website')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Featured testimonial'),

                        DateTimePicker::make('published_at')
                            ->label('Publish Date & Time')
                            ->seconds(false),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }
}
