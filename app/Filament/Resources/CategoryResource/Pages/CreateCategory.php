<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Exceptions\Category\CategoryFullPathConflictException;
use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return parent::handleRecordCreation($data);
        } catch (CategoryFullPathConflictException $e) {
            Notification::make()
                ->title('Конфликт путей')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt(); // Останавливаем процесс
        }
    }
}
