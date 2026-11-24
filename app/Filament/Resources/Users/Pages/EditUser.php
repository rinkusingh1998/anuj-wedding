<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function beforeSave(): void
    {
        $role = $this->data['role'] ?? null;

        if ($this->record->isAdmin() && $role !== 'admin' && User::query()->where('role', 'admin')->count() <= 1) {
            Notification::make()
                ->danger()
                ->title('At least one admin is required')
                ->send();

            $this->halt();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (User $record): bool => $record->getKey() !== auth()->id())
                ->before(function (User $record, DeleteAction $action): void {
                    if ($record->isAdmin() && User::query()->where('role', 'admin')->count() <= 1) {
                        $action->cancel();
                    }
                }),
        ];
    }
}
