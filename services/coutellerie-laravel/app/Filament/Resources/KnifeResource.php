<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KnifeResource\Pages;
use App\Filament\Resources\KnifeResource\RelationManagers;
use App\Models\Knife;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class KnifeResource extends Resource
{
    protected static ?string $model = Knife::class;

    // protected static ?string $navigationIcon = 'heroicon-o-knife';

    protected static ?string $navigationLabel = 'Couteaux';

    protected static ?string $modelLabel = 'Couteau';

    protected static ?string $pluralModelLabel = 'Couteaux';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('category')
                    ->label('Catégorie')
                    ->options(fn () => Knife::getCategoriesForSelect())
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('category')
                            ->label('Nouvelle catégorie')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return $data['category'];
                    })
                    ->required()
                    ->helperText('Sélectionnez une catégorie existante ou créez-en une nouvelle'),

                Forms\Components\TextInput::make('type')
                    ->label('Type')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(3),

                Forms\Components\FileUpload::make('images')
                    ->label('Images')
                    ->multiple()
                    ->image()
                    ->disk('public')
                    ->directory('knives')
                    ->maxFiles(5)
                    ->reorderable()
                    ->downloadable()
                    ->previewable()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('length')
                    ->label('Longueur')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('material')
                    ->label('Matériau')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('price')
                    ->label('Prix (€)')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Images')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Catégorie')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->searchable(),

                Tables\Columns\TextColumn::make('length')
                    ->label('Longueur'),

                Tables\Columns\TextColumn::make('material')
                    ->label('Matériau'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Catégorie')
                    ->options(function () {
                        return Knife::getCategoriesForSelect();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKnives::route('/'),
            'create' => Pages\CreateKnife::route('/create'),
            'edit' => Pages\EditKnife::route('/{record}/edit'),
        ];
    }
}
