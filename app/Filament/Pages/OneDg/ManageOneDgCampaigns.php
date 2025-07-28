<?php

namespace App\Filament\Pages\OneDg;

// Updated model imports
use App\Models\OneDgCampaign;
use App\Models\OneDgCampaignConfig;
use App\Services\OneDgApiService;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ManageOneDgCampaigns extends Page implements HasForms, HasTable
{
    use HasPageShield;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    // Updated view path
    protected static string $view = 'filament.pages.one-dg.manage-onedg-campaigns';

    protected static ?string $navigationGroup = 'OneDg Api Management';

    protected static ?string $navigationLabel = 'Chiến dịch (Beta)';

    protected static ?int $navigationSort = 4;

    public ?string $activeTab = 'campaigns';

    protected function getServicesOptions(OneDgApiService $apiService): array
    {
        return collect($apiService->getServices() ?? [])
            ->pluck('name', 'service')
            ->all();
    }

    public function table(Table $table): Table
    {
        return $this->activeTab === 'campaigns'
            ? $this->campaignsTable($table)
            : $this->configsTable($table);
    }

    protected function campaignsTable(Table $table): Table
    {
        return $table
            ->query(OneDgCampaign::query()) // Updated model
            ->columns([
                TextColumn::make('id')->label('#'),
                TextColumn::make('channel_link')->label('Kênh')->limit(40),
                TextColumn::make('campaignConfig.name')->label('Cấu hình'),
                TextColumn::make('expires_at')->label('Ngày kết thúc')->dateTime(),
                TextColumn::make('status')->label('Trạng thái')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'expired' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->form(fn (OneDgApiService $api) => $this->getCampaignFormSchema($api)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('createCampaign')
                    ->label('Tạo chiến dịch')
                    ->form(fn (OneDgApiService $api) => $this->getCampaignFormSchema($api))
                    ->action(fn (array $data) => OneDgCampaign::create($data)), // Updated model
            ]);
    }

    protected function configsTable(Table $table): Table
    {
        return $table
            ->query(OneDgCampaignConfig::query()) // Updated model
            ->columns([
                TextColumn::make('id')->label('#'),
                TextColumn::make('name')->label('Tên cấu hình'),
                TextColumn::make('services')
                    ->label('Số dịch vụ')
                    ->formatStateUsing(fn ($state) => count($state ?? []).' dịch vụ'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->form(fn (OneDgApiService $api) => $this->getConfigFormSchema($api)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('createConfig')
                    ->label('Tạo cấu hình')
                    ->form(fn (OneDgApiService $api) => $this->getConfigFormSchema($api))
                    ->action(fn (array $data) => OneDgCampaignConfig::create($data)), // Updated model
            ]);
    }

    protected function getCampaignFormSchema(OneDgApiService $apiService): array
    {
        return [
            TextInput::make('channel_link')->label('Link kênh')->url()->required(),
            DateTimePicker::make('expires_at')->label('Thời gian kết thúc'),
            Select::make('onedg_campaign_config_id') // Updated foreign key
                ->label('Cấu hình')
                ->options(OneDgCampaignConfig::pluck('name', 'id')) // Updated model
                ->required(),
        ];
    }

    protected function getConfigFormSchema(OneDgApiService $apiService): array
    {
        return [
            TextInput::make('name')->label('Tên cấu hình')->required(),
            Repeater::make('services')
                ->label('Dịch vụ')
                ->schema([
                    Select::make('service_id')
                        ->label('Dịch vụ')
                        ->options($this->getServicesOptions($apiService))
                        ->searchable()
                        ->required(),
                    TextInput::make('quantity')->label('Số lượng')->numeric()->required(),
                ])
                ->columns(2)
                ->addActionLabel('Thêm dịch vụ')
                ->required(),
        ];
    }
}
