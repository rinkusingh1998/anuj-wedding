<?php

namespace App\Filament\Forms\Components;

use App\Models\Media;
use App\Support\MediaLibrary;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * File upload that also previews images already stored as CDN URLs.
 *
 * Older records can hold a full https:// image URL while new uploads are stored
 * as a path on the public disk. Filament's default preview only understands disk
 * paths, so the image would look missing in the admin panel. This component
 * resolves both cases through MediaLibrary::url().
 *
 * It also connects every upload to the central Media Library:
 *  - a new upload is registered as a media row automatically
 *  - the "Choose from library" action re-uses an image that already exists
 */
class MediaUpload extends FileUpload
{
    /**
     * Image types accepted by every upload field on the website.
     *
     * @var list<string>
     */
    private const IMAGE_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/avif',
        'image/gif',
    ];

    /**
     * Request-scoped "library has items" check, so a form with many image
     * fields only runs the query once.
     */
    private static function libraryHasItems(): bool
    {
        return once(fn (): bool => Media::query()->exists());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->getUploadedFileUsing(static function (string $file): ?array {
            $url = MediaLibrary::url($file);

            if (! $url) {
                return null;
            }

            return [
                'name' => basename((string) (parse_url($url, PHP_URL_PATH) ?: $url)),
                'size' => 0,
                'type' => null,
                'url' => $url,
            ];
        });

        // Only real raster images. SVG is intentionally excluded because it can
        // contain scripts and is served from the same domain.
        $this->acceptedFileTypes(self::IMAGE_TYPES);

        $this->saveUploadedFileUsing(function (MediaUpload $component, TemporaryUploadedFile $file): ?string {
            $path = $component->saveUploadedFile($file);

            if (filled($path)) {
                Media::register(
                    path: (string) $path,
                    disk: $component->getDiskName(),
                    collection: 'general',
                );
            }

            return $path;
        });

        $this->hintAction(
            Action::make('chooseFromMediaLibrary')
                ->label('Choose from library')
                ->icon('heroicon-o-photo')
                ->visible(fn (): bool => self::libraryHasItems())
                ->modalHeading('Choose an image from the Media Library')
                ->modalDescription('Re-use an image that is already uploaded. You can manage titles, alt text and captions in Media Library.')
                ->schema([
                    Select::make('media_id')
                        ->label('Image')
                        ->options(fn (): array => Media::query()
                            ->orderByDesc('created_at')
                            ->limit(300)
                            ->get()
                            ->mapWithKeys(fn (Media $media): array => [
                                $media->getKey() => trim(($media->title ?: $media->filename ?: $media->path).' ('.$media->collection.')'),
                            ])
                            ->all())
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $media = Media::query()->find($data['media_id'] ?? null);
                    $value = $media?->storedValue();

                    if (blank($value)) {
                        return;
                    }

                    if ($this->isMultiple()) {
                        $state = collect($this->getState())->filter()->push($value)->unique()->values()->all();
                        $this->state($state);

                        return;
                    }

                    $this->state($value);
                }),
        );
    }
}
