<?php

namespace App\Filament\Resources\Features\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Features\FeatureResource;
use Filament\Resources\Pages\EditRecord;

class EditFeature extends EditRecord
{
    protected static string $resource = FeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
