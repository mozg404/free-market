<?php

namespace App\Filament\Resources\FeatureResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\FeatureResource;
use Filament\Resources\Pages\ListRecords;

class ListFeatures extends ListRecords
{
    protected static string $resource = FeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
