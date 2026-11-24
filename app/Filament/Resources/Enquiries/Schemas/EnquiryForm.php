<?php

namespace App\Filament\Resources\Enquiries\Schemas;

use App\Models\Enquiry;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer')
                    ->schema([
                        TextInput::make('name')->label('Name')->disabled()->dehydrated(false),
                        TextInput::make('phone')->label('Phone')->disabled()->dehydrated(false),
                        TextInput::make('email')->label('Email')->disabled()->dehydrated(false),
                        TextInput::make('city')->label('City')->disabled()->dehydrated(false),
                        TextInput::make('service')->label('Service')->disabled()->dehydrated(false),
                        TextInput::make('event_type')->label('Event Type')->disabled()->dehydrated(false),
                        TextInput::make('event_date')->label('Event Date')->disabled()->dehydrated(false),
                        TextInput::make('event_time')->label('Event Time')->disabled()->dehydrated(false),
                        TextInput::make('event_address')->label('Venue / Address')->disabled()->dehydrated(false)->columnSpanFull(),
                        TextInput::make('budget')->label('Budget')->disabled()->dehydrated(false),
                        Textarea::make('message')->label('Message')->rows(4)->disabled()->dehydrated(false)->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Lead Status')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options(Enquiry::STATUSES)
                            ->required(),

                        TextInput::make('source_page')->label('Source Page')->disabled()->dehydrated(false),
                        TextInput::make('source_url')->label('Source URL')->disabled()->dehydrated(false)->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
