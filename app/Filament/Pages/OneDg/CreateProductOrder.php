<?php

// app/Filament/Pages/OneDg/CreateProductOrder.php

namespace App\Filament\Pages\OneDg;

use App\Filament\Traits\LogsPageActivity; // Import the trait
use App\Services\OneDgApiService;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class CreateProductOrder extends Page implements HasForms
{
    use HasPageShield;
    use InteractsWithForms;
    use LogsPageActivity; // Use the trait

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static string $view = 'filament.pages.one-dg.manage-product-orders';

    protected static ?string $navigationGroup = 'OneDg Api Management';

    protected static ?string $navigationLabel = 'Đơn hàng Sản phẩm';

    protected static ?int $navigationSort = 4;

    public ?string $activeTab = 'create';

    // Create Form
    public ?array $createData = [];

    public Collection $products;

    // Status Form
    public ?array $statusData = [];

    public ?array $statusResult = null;

    public function mount(OneDgApiService $apiService): void
    {
        $this->products = collect($apiService->getProducts() ?? []);
        $this->createForm->fill();
        $this->statusForm->fill();
    }

    protected function getForms(): array
    {
        return ['createForm', 'statusForm'];
    }

    public function createForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product')
                    ->label('Sản phẩm')
                    ->options($this->products->pluck('name', 'product'))
                    ->searchable()->required(),
                TextInput::make('quantity')->label('Số lượng')->numeric()->required(),
                Textarea::make('require')->label('Thông tin yêu cầu')->rows(3),
            ])
            ->statePath('createData');
    }

    public function statusForm(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('orders')->label('Product Order IDs')->helperText('Nhập một hoặc nhiều ID, cách nhau bởi dấu phẩy hoặc xuống dòng.')->required(),
            ])
            ->statePath('statusData');
    }

    public function createProductOrder(OneDgApiService $apiService): void
    {
        $data = $this->createForm->getState();
        $response = $apiService->addProductOrder($data['product'], $data['quantity'], $data['require'] ?? null);

        if (isset($response['order'])) {
            Notification::make()->title('Thành công!')->body('Đã tạo đơn hàng sản phẩm ID: '.$response['order'])->success()->send();

            // Log the activity
            $this->logActivity('Đã tạo đơn hàng sản phẩm', [
                'order_id' => $response['order'],
                'product_id' => $data['product'],
                'quantity' => $data['quantity'],
                'require' => $data['require'] ?? null,
            ]);

            $this->createForm->fill();
        } else {
            Notification::make()->title('Thất bại')->body($response['error'] ?? 'Lỗi không xác định.')->danger()->send();
        }
    }

    public function checkStatus(OneDgApiService $apiService): void
    {
        $data = $this->statusForm->getState();
        $orderIds = preg_split('/[,\s]+/', $data['orders'], -1, PREG_SPLIT_NO_EMPTY);
        $this->statusResult = null;

        if (count($orderIds) === 1) {
            $response = $apiService->getProductOrderStatus($orderIds[0]);
            $this->statusResult = [$orderIds[0] => $response];
        } elseif (count($orderIds) > 1) {
            $this->statusResult = $apiService->getMultipleProductOrderStatuses($orderIds);
        }

        if (empty($this->statusResult)) {
            Notification::make()->title('Không tìm thấy')->warning()->send();
        }
    }
}
