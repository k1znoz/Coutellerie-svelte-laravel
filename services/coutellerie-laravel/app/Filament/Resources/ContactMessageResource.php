<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Filament\Resources\ContactMessageResource\RelationManagers;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Colors\Color;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Messages de contact';

    protected static ?string $modelLabel = 'Message de contact';

    protected static ?string $pluralModelLabel = 'Messages de contact';

    protected static ?int $navigationSort = 2;

    // Badge pour les nouveaux messages
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'new')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::where('status', 'new')->count() > 0 ? 'warning' : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations du contact')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(100)
                            ->disabled(),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(100)
                            ->disabled(),

                        Forms\Components\TextInput::make('subject')
                            ->label('Sujet')
                            ->maxLength(200)
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options(ContactMessage::getStatuses())
                            ->required()
                            ->default('new'),

                        Forms\Components\TextInput::make('ip_address')
                            ->label('Adresse IP')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Message')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->label('Message')
                            ->required()
                            ->rows(6)
                            ->disabled(),
                    ]),

                Forms\Components\Section::make('Informations système')
                    ->schema([
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Reçu le')
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('updated_at')
                            ->label('Dernière modification')
                            ->disabled(),
                    ])->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->formatStateUsing(fn (string $state): string => ContactMessage::getStatuses()[$state])
                    ->colors([
                        'warning' => 'new',
                        'success' => 'read',
                        'primary' => 'replied',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Sujet')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('message')
                    ->label('Message')
                    ->limit(100)
                    ->tooltip(function (ContactMessage $record): string {
                        return $record->message;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Reçu le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options(ContactMessage::getStatuses()),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Reçu à partir du'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Reçu jusqu\'au'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('markAsRead')
                    ->label('Marquer comme lu')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->action(fn (ContactMessage $record) => $record->markAsRead())
                    ->visible(fn (ContactMessage $record) => $record->status === 'new'),

                Tables\Actions\Action::make('markAsReplied')
                    ->label('Marquer comme répondu')
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    ->action(fn (ContactMessage $record) => $record->markAsReplied())
                    ->visible(fn (ContactMessage $record) => $record->status !== 'replied'),

                Tables\Actions\Action::make('sendEmail')
                    ->label('Répondre par email')
                    ->icon('heroicon-o-envelope')
                    ->url(fn (ContactMessage $record) => 'mailto:' . $record->email . '?subject=Re: ' . urlencode($record->subject))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make()
                    ->label('Voir/Éditer'),

                Tables\Actions\DeleteAction::make()
                    ->label('Supprimer'),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('markAsRead')
                    ->label('Marquer comme lus')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->action(fn ($records) => $records->each(fn ($record) => $record->markAsRead())),

                Tables\Actions\BulkAction::make('markAsReplied')
                    ->label('Marquer comme répondus')
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    ->action(fn ($records) => $records->each(fn ($record) => $record->markAsReplied())),

                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s'); // Actualisation automatique toutes les 30 secondes
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
