<?php

// app/Filament/Pages/OneDg/CreateServiceOrder.php -> Renamed to ManageServiceOrders.php
// The class name is also updated to reflect the new functionality.

namespace App\Filament\Pages\OneDg;

use App\Filament\Traits\LogsPageActivity;
use App\Services\OneDgApiService;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Computed; // Import the Computed attribute

class CreateServiceOrder extends Page implements HasForms
{
    use HasPageShield;
    use InteractsWithForms;
    use LogsPageActivity;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static string $view = 'filament.pages.one-dg.create-service-order';

    protected static ?string $navigationGroup = 'OneDg Api Management';

    // Updated navigation label to be more generic
    protected static ?string $navigationLabel = 'Tao đơn hàng Dịch vụ';

    protected static ?int $navigationSort = 2;

    // --- Properties for both tabs ---
    public ?string $activeTab = 'create';

    // Create Order properties
    public ?array $createData = [];

    public Collection $services;

    public Collection $platforms;

    public ?string $selectedPlatform = null;

    public ?array $selectedService = null;

    public float $charge = 0.0;

    // Check Status properties
    public ?array $checkStatusData = [];

    public ?array $orders = null;

    public array $selectedOrders = [];

    public function mount(OneDgApiService $apiService): void
    {
        $allServices = $apiService->getServices() ?? [];
        $this->services = collect($allServices);

        $this->platforms = $this->services->pluck('platform', 'platform')
            ->unique()
            ->filter()
            ->sort();

        $this->createForm->fill();
        $this->checkStatusForm->fill();
    }

    // --- Computed property for category options ---
    // This will be cached and only re-calculated when $this->selectedPlatform changes.
    #[Computed]
    public function categoryOptions(): array
    {
        $filtered = $this->services;
        if ($this->selectedPlatform) {
            $filtered = $filtered->where('platform', $this->selectedPlatform);
        }

        return $filtered->pluck('category', 'category')->unique()->sort()->all();
    }

    // --- Methods for Create Order Tab ---

    public function selectPlatform(?string $platform): void
    {
        $this->selectedPlatform = $this->selectedPlatform === $platform ? null : $platform;
        $this->createData['category'] = null;
        $this->createData['service'] = null;
        $this->selectedService = null;
        $this->charge = 0.0;
    }

    public function updateCharge(?string $quantity): void
    {
        $this->charge = ($this->selectedService && is_numeric($quantity) && $quantity > 0)
            ? ((float) $this->selectedService['rate'] / 1000) * (int) $quantity
            : 0.0;
    }

    public function create(OneDgApiService $apiService): void
    {
        $formData = $this->createForm->getState();
        $optionalParams = ! empty($formData['comments']) ? ['comments' => $formData['comments']] : [];

        $response = $apiService->addOrder($formData['service'], $formData['link'], $formData['quantity'], $optionalParams);

        if (isset($response['order'])) {
            Notification::make()->title('Thành công!')->body('Đã tạo đơn hàng ID: '.$response['order'])->success()->send();

            // --- LOG THE ACTIVITY ---
            $this->logActivity('Đã tạo đơn hàng dịch vụ', [
                'order_id' => $response['order'],
                'service_id' => $formData['service'],
                'link' => $formData['link'],
                'quantity' => $formData['quantity'],
                'charge' => $this->charge,
            ]);
            // --- END LOGGING ---

            $this->createForm->fill();
            $this->selectedService = null;
            $this->charge = 0.0;
        } else {
            Notification::make()->title('Có lỗi xảy ra')->body($response['error'] ?? 'Không thể tạo đơn hàng.')->danger()->send();
        }
    }

    // --- Methods for Check Status Tab ---

    public function checkStatuses(OneDgApiService $apiService): void
    {
        $this->orders = null;
        $this->selectedOrders = [];
        $formData = $this->checkStatusForm->getState();
        $orderIds = preg_split('/[,\s]+/', $formData['order_ids'], -1, PREG_SPLIT_NO_EMPTY);

        if (empty($orderIds)) {
            Notification::make()->title('Vui lòng nhập Order ID')->warning()->send();

            return;
        }

        $this->orders = (count($orderIds) === 1)
            ? [$orderIds[0] => $apiService->getOrderStatus($orderIds[0])]
            : $apiService->getMultipleOrderStatuses($orderIds);

        if (empty($this->orders)) {
            Notification::make()->title('Không tìm thấy kết quả')->warning()->send();
        }
    }

    public function refillSelected(OneDgApiService $apiService): void
    {
        if (empty($this->selectedOrders)) {
            return;
        }

        $response = $apiService->createMultipleRefills(array_keys($this->selectedOrders));

        if (isset($response['refill'])) {
            Notification::make()->title('Yêu cầu Refill thành công!')->body('Refill ID: '.$response['refill'])->success()->send();
        } else {
            Notification::make()->title('Yêu cầu Refill thất bại')->body($response['error'] ?? 'Lỗi không xác định.')->danger()->send();
        }

        $this->selectedOrders = [];
    }

    public function getStatusColor(string $status): string
    {
        return match (strtolower($status)) {
            'completed' => 'success',
            'in progress', 'processing' => 'primary',
            'partial' => 'warning',
            'canceled' => 'danger',
            'pending' => 'gray',
            default => 'gray',
        };
    }

    // --- Form Definitions ---

    protected function getForms(): array
    {
        return [
            'createForm',
            'checkStatusForm',
        ];
    }

    public function createForm(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    Section::make('Đặt hàng mới')
                        ->schema([
                            Placeholder::make('platforms')->content(view('filament.partials.platform-buttons', ['platforms' => $this->platforms])),
                            TextInput::make('search')->label('Quick search service')->placeholder('Tìm kiếm...')->live(debounce: 500)
                                ->afterStateUpdated(function (Set $set) {
                                    $set('category', null);
                                    $this->selectedPlatform = null;
                                }),
                            Select::make('category')->label('Danh mục')
                                ->options($this->categoryOptions) // Use the computed property
                                ->live()->afterStateUpdated(fn (Set $set) => $set('service', null)),
                            Select::make('service')->label('Dịch vụ')
                                ->options(function (Get $get) {
                                    return $this->services
                                        ->when($get('search'), fn ($q, $s) => $q->filter(fn ($i) => str_contains(strtolower($i['name']), strtolower($s)) || $i['service'] == $s))
                                        ->when($get('category'), fn ($q, $c) => $q->where('category', $c))
                                        ->pluck('name', 'service');
                                })
                                ->searchable()->live()->required()
                                ->afterStateUpdated(function ($state) {
                                    $this->selectedService = $this->services->firstWhere('service', $state);
                                    $this->updateCharge($this->createData['quantity'] ?? 0);
                                }),
                            TextInput::make('link')->label('Link')->url()->required(),
                            TextInput::make('quantity')->label('Số lượng')->numeric()->live(onBlur: true)->required()
                                ->afterStateUpdated(fn ($state) => $this->updateCharge($state)),
                            Placeholder::make('charge')->label('Phí')->content(fn () => new HtmlString('<span class="text-lg font-bold text-primary-500">$'.number_format($this->charge, 4).'</span>')),
                            Textarea::make('comments')->label('Bình luận')->rows(4)
                                ->visible(fn () => $this->selectedService && $this->selectedService['type'] === 'Custom Comments'),
                        ])
                        ->columnSpan(2),
                    Section::make('Chi tiết Dịch vụ')
                        ->schema([
                            Placeholder::make('service_details')
                                ->content(function () {
                                    if (! $this->selectedService) {
                                        return 'Vui lòng chọn một dịch vụ.';
                                    }
                                    $desc = '<div class="prose prose-sm dark:prose-invert">';
                                    $desc .= '<strong>ID:</strong> '.$this->selectedService['service'].'<br>';
                                    $desc .= '<strong>Giá / 1000:</strong> <span class="font-bold text-primary-500">$'.$this->selectedService['rate'].'</span><br>';
                                    $desc .= '<strong>Min:</strong> '.$this->selectedService['min'].' | <strong>Max:</strong> '.$this->selectedService['max'].'<br>';
                                    $desc .= '<hr class="my-2">';
                                    $desc .= $this->selectedService['desc'] ?? 'Không có mô tả.';
                                    $desc .= '</div>';

                                    return new HtmlString($desc);
                                }),
                        ])
                        ->columnSpan(1),
                ]),
            ])
            ->statePath('createData');
    }

    public function checkStatusForm(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('order_ids')
                    ->label('Order IDs')
                    ->placeholder('Nhập một hoặc nhiều Order ID, mỗi ID trên một dòng hoặc cách nhau bởi dấu phẩy.')
                    ->rows(8)
                    ->required(),
            ])
            ->statePath('checkStatusData');
    }
}
