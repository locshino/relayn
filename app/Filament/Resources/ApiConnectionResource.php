<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApiConnectionResource\Pages;
use App\Models\ApiConnection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApiConnectionResource extends Resource
{
    protected static ?string $model = ApiConnection::class;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('api_url')
                    ->label('API URL')
                    ->url() // Thêm validation cho URL
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('api_key')
                    ->label('API Key')
                    ->password() // Ẩn giá trị khi nhập
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('api_url'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiConnections::route('/'),
            'create' => Pages\CreateApiConnection::route('/create'),
            'edit' => Pages\EditApiConnection::route('/{record}/edit'),
        ];
    }
}
