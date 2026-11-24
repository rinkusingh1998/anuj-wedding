<?php

namespace App\Filament\Resources\Media\Schemas;

use App\Filament\Forms\Components\MediaUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Media File')
                    ->schema([
                        MediaUpload::make('path')
                            ->label('Upload File')
                            ->helperText('Upload an image to the public disk. Leave empty to keep the existing file.')
                            ->image()
                            ->disk('public')
                            ->directory('media')
                            ->visibility('public')
                            ->fetchFileInformation(false)
                            ->imageEditor()
                            ->maxSize(10240)
                            ->columnSpanFull(),

                        TextInput::make('url')
                            ->label('External URL (optional)')
                            ->helperText('Use this instead of an upload to reference an external CDN file.')
                            ->url()
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        TextInput::make('title')
                            ->label('Title')
                            ->maxLength(255),

                        TextInput::make('alt_text')
                            ->label('Alt Text')
                            ->helperText('Required for accessibility and image SEO.')
                            ->maxLength(255),

                        Textarea::make('caption')
                            ->label('Caption')
                            ->rows(2)
                            ->columnSpanFull(),

                        Select::make('collection')
                            ->label('Collection')
                            ->options([
                                'general' => 'General',
                                'hero' => 'Hero / Banner',
                                'gallery' => 'Gallery',
                                'featured' => 'Featured Image',
                                'logo' => 'Logo',
                                'og' => 'Social Sharing',
                            ])
                            ->default('general'),

                        TextInput::make('folder')
                            ->label('Folder')
                            ->maxLength(120),

                        TextInput::make('filename')
                            ->label('File Name')
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }
}
