<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Admin Management';

    public static function form(Form $form): Form
    {
        // A read-only form to view details
        return $form
            ->schema([
                TextInput::make('causer.name')->label('User')->disabled(),
                TextInput::make('description')->label('Action')->disabled(),
                KeyValue::make('properties')->label('Details')->disabled(),
                TextInput::make('created_at')->label('Timestamp')->disabled(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('causer.name')->label('User')->sortable()->searchable(),
                TextColumn::make('description')->label('Action')->searchable(),
                TextColumn::make('created_at')->label('Timestamp')->dateTime()->sortable(),
            ])
            ->filters([
                // You can add filters here, e.g., by user or date
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]) // Disable bulk actions
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }

    // Make the resource read-only
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
