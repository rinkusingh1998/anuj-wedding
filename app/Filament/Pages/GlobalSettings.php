<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\MediaUpload;
use App\Models\SeoSetting;
use App\Providers\AppServiceProvider;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Cache;

/**
 * One screen for every global website setting.
 *
 * The fields are generated from SeoSetting::DEFAULTS, so adding a new setting
 * to the model automatically adds it to this admin page. Groups include
 * Branding, Default SEO, Business, Analytics, Header & Footer, Mega Menu,
 * Mobile Menu and Section Labels.
 *
 * @property-read Schema $form
 */
class GlobalSettings extends Page
{
    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    protected static ?string $title = 'Global Website Settings';

    protected static ?string $navigationLabel = 'Global Settings';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.global-settings';

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function mount(): void
    {
        $this->form->fill(SeoSetting::values());
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            $this->getFormContentComponent(),
        ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                Actions::make($this->getFormActions())
                    ->alignment('start')
                    ->key('form-actions'),
            ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        $sections = [];

        foreach (SeoSetting::grouped() as $group => $settings) {
            $fields = [];

            foreach ($settings as $key => $setting) {
                $fields[] = match ($setting['type']) {
                    'textarea' => Textarea::make($key)
                        ->label($setting['label'])
                        ->rows(3)
                        ->columnSpanFull(),

                    'toggle' => Toggle::make($key)
                        ->label($setting['label'])
                        ->default((bool) $setting['default'])
                        ->inline(false),

                    'select' => Select::make($key)
                        ->label($setting['label'])
                        ->options($setting['options'] ?? [])
                        ->selectablePlaceholder(false),

                    'image' => MediaUpload::make($key)
                        ->label($setting['label'])
                        ->helperText('Upload an image or paste a public URL.')
                        ->image()
                        ->disk('public')
                        ->directory('settings')
                        ->visibility('public')
                        ->fetchFileInformation(false)
                        ->imageEditor()
                        ->maxSize(5120)
                        ->columnSpanFull(),

                    default => TextInput::make($key)
                        ->label($setting['label'])
                        ->maxLength(1000),
                };
            }

            $sections[] = Section::make($group)
                ->schema($fields)
                ->columns(2)
                ->collapsible();
        }

        return $schema->components($sections);
    }

    /**
     * @return list<Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save settings')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach (SeoSetting::DEFAULTS as $key => $setting) {
            if (! array_key_exists($key, $data)) {
                continue;
            }

            $value = $data[$key];

            if ($setting['type'] === 'toggle') {
                $value = $value ? '1' : '0';
            }

            SeoSetting::put($key, is_scalar($value) ? (string) $value : null, $setting['group']);
        }

        Cache::forget('site-business-profile');

        foreach (['header', 'footer', 'mobile'] as $menu) {
            Cache::forget(AppServiceProvider::NAVIGATION_CACHE_PREFIX.$menu);
        }

        Cache::forget(AppServiceProvider::LOCATIONS_CACHE_KEY);

        Notification::make()
            ->success()
            ->title('Settings saved')
            ->body('The website will now use these settings.')
            ->send();
    }
}
