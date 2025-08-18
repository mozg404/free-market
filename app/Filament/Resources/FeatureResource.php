<?php

namespace App\Filament\Resources;

use App\Enum\FeatureType;
use App\Filament\Resources\FeatureResource\Pages;
use App\Filament\Resources\FeatureResource\RelationManagers;
use App\Models\Feature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->required()
                    ->preload()
                    ->searchable()
                    ->native(false)
                    ->hint('Выберите одну или несколько категорий'),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

//                Forms\Components\TextInput::make('key')
//                    ->required()
//                    ->unique(ignoreRecord: true)
//                    ->maxLength(255)
//                    ->regex('/^[a-z0-9_]+$/') // Только латиница и подчёркивания
//                    ->hint('Например: edition_type'),

                Forms\Components\Select::make('type')
                    ->options(FeatureType::names())
                    ->required()
                    ->live(),

                Forms\Components\KeyValue::make('options')
                    ->nullable()
                    ->visible(fn (Forms\Get $get) => $get('type') === 'select')
                    ->keyLabel('Значение (для кода)')
                    ->valueLabel('Отображаемый текст'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                // Обновляем отображение категорий (теперь их может быть несколько)
                Tables\Columns\TextColumn::make('categories.name')
                    ->label('Категории')
                    ->badge()
                    ->separator(', ')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

//                Tables\Columns\TextColumn::make('key')
//                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state->value) {
                        'select' => 'Select',
                        'text' => 'Text',
                        default => ucfirst($state->value)
                    }),

//                Tables\Columns\IconColumn::make('is_required')
//                    ->boolean()
//                    ->label('Обязательное'),
            ])
            ->filters([
                // Обновляем фильтр для работы с Many-to-Many
                Tables\Filters\SelectFilter::make('categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CategoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeatures::route('/'),
            'create' => Pages\CreateFeature::route('/create'),
            'edit' => Pages\EditFeature::route('/{record}/edit'),
        ];
    }
}
