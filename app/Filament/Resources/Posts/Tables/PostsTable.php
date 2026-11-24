<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->square()
                    ->height(40),

                TextColumn::make('title')
                    ->label('Article')
                    ->weight(FontWeight::Bold)
                    ->description(fn (Post $record): string => '/blog/'.$record->slug)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('author')
                    ->label('Author')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime()
                    ->placeholder('Draft')
                    ->sortable(),

                ToggleColumn::make('is_visible')
                    ->label('Live')
                    ->onColor('success')
                    ->offColor('danger'),

                ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->onColor('warning')
                    ->offColor('gray')
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('post_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->placeholder('All articles')
                    ->trueLabel('Only published')
                    ->falseLabel('Only drafts'),

                TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->trueLabel('Only featured')
                    ->falseLabel('Only normal'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View article')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Post $record): string => $record->url())
                    ->openUrlInNewTab(),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
