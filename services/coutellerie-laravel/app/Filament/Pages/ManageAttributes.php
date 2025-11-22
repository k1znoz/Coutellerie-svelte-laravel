<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Type;
use App\Models\Material;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms;
use Livewire\Attributes\Url;

class ManageAttributes extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static string $view = 'filament.pages.manage-attributes';

    protected static ?string $navigationLabel = 'Attributs';

    protected static ?string $title = 'Gestion des Attributs';

    protected static ?int $navigationSort = 2;

    #[Url]
    public string $activeTab = 'categories';

    public function table(Table $table): Table
    {
        return match($this->activeTab) {
            'categories' => $this->getCategoriesTable($table),
            'types' => $this->getTypesTable($table),
            'materials' => $this->getMaterialsTable($table),
            default => $this->getCategoriesTable($table),
        };
    }

    protected function getCategoriesTable(Table $table): Table
    {
        return $table
            ->query(Category::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255)
                            ->unique(Category::class, 'name', ignoreRecord: true),
                    ]),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255)
                            ->unique(Category::class, 'name'),
                    ]),
            ])
            ->paginated([10, 25, 50]);
    }

    protected function getTypesTable(Table $table): Table
    {
        return $table
            ->query(Type::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255)
                            ->unique(Type::class, 'name', ignoreRecord: true),
                    ]),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255)
                            ->unique(Type::class, 'name'),
                    ]),
            ])
            ->paginated([10, 25, 50]);
    }

    protected function getMaterialsTable(Table $table): Table
    {
        return $table
            ->query(Material::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255)
                            ->unique(Material::class, 'name', ignoreRecord: true),
                    ]),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255)
                            ->unique(Material::class, 'name'),
                    ]),
            ])
            ->paginated([10, 25, 50]);
    }
}