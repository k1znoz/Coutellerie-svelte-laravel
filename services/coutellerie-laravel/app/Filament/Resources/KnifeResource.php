<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KnifeResource\Pages;
use App\Filament\Resources\KnifeResource\RelationManagers;
use App\Models\Knife;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class KnifeResource extends Resource
{
    protected static ?string $model = Knife::class;

    protected static ?string $navigationIcon = 'heroicon-o-scissors';

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

                // --- DEBUT DES MODIFICATIONS ---
                Select::make('category_id')
                    ->label('Catégorie')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom de la catégorie')
                            ->required()
                            ->maxLength(255)
                            ->unique('categories', 'name'),
                    ])
                    ->required(),

                Select::make('type_id')
                    ->label('Type')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom du type')
                            ->required()
                            ->maxLength(255)
                            ->unique('types', 'name'),
                    ])
                    ->required(),
                
                Select::make('material_id')
                    ->label('Matériau')
                    ->relationship('material', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom du matériau')
                            ->required()
                            ->maxLength(255)
                            ->unique('materials', 'name'),
                    ])
                    ->required(),
                // --- FIN DES MODIFICATIONS ---

                Forms\Components\TextInput::make('length')
                    ->label('Longueur')
                    ->required()
                    ->numeric(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('price')
                    ->label('Prix')
                    ->required()
                    ->numeric()
                    ->prefix('€'),

                Forms\Components\FileUpload::make('images')
                    ->label('Images')
                    ->multiple()
                    ->directory('knives')
                    ->image()
                    ->reorderable()
                    ->appendFiles()
                    ->panelLayout('grid'),

                Forms\Components\Toggle::make('available')
                    ->label('Disponible')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Images')
                    ->disk('public')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),

                // --- MODIFICATION : Afficher le nom de la relation plutôt que l'ID ---
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type.name')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('material.name')
                    ->label('Matériau')
                    ->searchable()
                    ->sortable(),
                // --- FIN DE LA MODIFICATION ---

                Tables\Columns\TextColumn::make('length')
                    ->label('Longueur')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('available')
                    ->label('Disponible')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
