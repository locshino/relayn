<?php

// app/Filament/Pages/OneDg/CreateMassOrder.php

namespace App\Filament\Pages\OneDg;

use App\Filament\Traits\LogsPageActivity; // Import the trait
use App\Services\OneDgApiService;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CreateMassOrder extends Page implements HasForms
{
    use HasPageShield;
    use InteractsWithForms;
    use LogsPageActivity;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static string $view = 'filament.pages.one-dg.create-mass-order';

    protected static ?string $navigationGroup = 'OneDg Api Management';

    protected static ?string $navigationLabel = 'Đặt số lượng lớn';

    protected static ?int $navigationSort = 3;

    public ?array $data = [];

    public ?array $results = [];

    protected ?array $serviceOptions = null;

    public string $inputMode = 'repeater';

    public function mount(): void
    {
        $this->repeaterForm->fill([
            'orders' => [['service_id' => null, 'link' => '', 'quantity' => null]],
        ]);
        $this->textareaForm->fill();
    }

    protected function getServiceOptions(OneDgApiService $apiService): array
    {
        if ($this->serviceOptions === null) {
            $this->serviceOptions = collect($apiService->getServices() ?? [])
                ->pluck('name', 'service')
                ->all();
        }

        return $this->serviceOptions;
    }

    protected function getForms(): array
    {
        return ['repeaterForm', 'textareaForm'];
    }

    public function repeaterForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Danh sách đơn hàng')
                    ->description('Thêm từng đơn hàng hoặc nhập từ file Excel.')
                    ->headerActions([
                        // New action to download the sample file
                        Action::make('downloadSample')
                            ->label('Tải file mẫu')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->color('gray')
                            ->url(route('download.mass-order-template'), shouldOpenInNewTab: true),

                        Action::make('importExcel')
                            ->label('Nhập từ Excel')
                            ->icon('heroicon-o-arrow-up-tray')
                            ->form([
                                FileUpload::make('attachment')
                                    ->label('File Excel (.xlsx, .xls)')
                                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                                    ->required()
                                    ->helperText('File cần có 3 cột: Cột A (service_id), Cột B (link), Cột C (quantity).'),
                            ])
                            ->action(function (array $data) {
                                $this->importFromExcel($data['attachment']);
                            }),
                    ])
                    ->schema([
                        Repeater::make('orders')
                            ->label('')
                            ->schema([
                                Select::make('service_id')->label('Dịch vụ')->options(fn (OneDgApiService $api) => $this->getServiceOptions($api))->searchable()->required(),
                                TextInput::make('link')->label('Link')->url()->required(),
                                TextInput::make('quantity')->label('Số lượng')->numeric()->required(),
                            ])
                            ->columns(3)
                            ->addActionLabel('Thêm đơn hàng')
                            ->defaultItems(1)
                            ->reorderable(false),
                    ]),
            ])
            ->statePath('data.repeater');
    }

    public function textareaForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Danh sách đơn hàng (nhập nhanh)')
                    ->schema([
                        Textarea::make('orders_text')
                            ->label('')
                            ->placeholder("1|http://example.com/link1|1000\n2|http://example.com/link2|500")
                            ->helperText('Mỗi đơn hàng trên một dòng theo định dạng: service_id|link|quantity')
                            ->rows(15)
                            ->required(),
                    ]),
            ])
            ->statePath('data.textarea');
    }

    public function importFromExcel(string $filePath): void
    {
        try {
            $importedData = Excel::toCollection(new class {}, storage_path('app/'.$filePath))->first();

            if ($importedData->isEmpty()) {
                Notification::make()->title('File rỗng')->warning()->send();

                return;
            }

            $orders = $importedData->map(function ($row) {
                return [
                    'service_id' => $row[0] ?? null,
                    'link' => $row[1] ?? null,
                    'quantity' => $row[2] ?? null,
                ];
            })->filter(function ($order) {
                return ! empty($order['service_id']) && ! empty($order['link']) && ! empty($order['quantity']);
            })->values()->toArray();

            if (empty($orders)) {
                Notification::make()->title('File rỗng hoặc sai định dạng')->body('Không tìm thấy dữ liệu hợp lệ trong file Excel.')->warning()->send();

                return;
            }

            $this->repeaterForm->fill(['orders' => $orders]);

            Notification::make()->title('Nhập thành công')->body('Đã nhập '.count($orders).' đơn hàng. Vui lòng kiểm tra lại trước khi gửi.')->success()->send();

        } catch (\Exception $e) {
            Notification::make()->title('Lỗi khi nhập file')->body('Vui lòng kiểm tra lại định dạng file.')->danger()->send();
            Log::error('Excel import failed: '.$e->getMessage());
        }
    }

    public function submitMassOrder(OneDgApiService $apiService): void
    {
        $this->results = [];
        $submittedCount = 0;

        if ($this->inputMode === 'repeater') {
            $orders = $this->repeaterForm->getState()['orders'] ?? [];
            $this->processRepeaterOrders($apiService, $orders);
        } else {
            $formData = $this->textareaForm->getState();
            $orderLines = preg_split('/\\r\\n|\\r|\\n/', $formData['orders_text'] ?? '');
            $this->processTextareaOrders($apiService, $orderLines);
        }

        $submittedCount = collect($this->results)->where('status', 'success')->count();

        if (count($this->results) > 0) {
            Notification::make()->title('Hoàn tất xử lý!')->body("Đã gửi thành công {$submittedCount} / ".count($this->results).' đơn hàng.')->success()->send();

            $this->logActivity('Đã gửi đơn hàng số lượng lớn', [
                'input_mode' => $this->inputMode,
                'total_orders' => count($this->results),
                'successful_orders' => $submittedCount,
                'failed_orders' => count($this->results) - $submittedCount,
            ]);

        } else {
            Notification::make()->title('Lỗi')->body('Không có đơn hàng hợp lệ nào để gửi đi.')->warning()->send();
        }

        $this->repeaterForm->fill(['orders' => [['service_id' => null, 'link' => '', 'quantity' => null]]]);
        $this->textareaForm->fill();
    }

    private function processRepeaterOrders(OneDgApiService $apiService, array $orders): void
    {
        foreach ($orders as $index => $orderData) {
            $this->sendOrderRequest('Đơn hàng #'.($index + 1), $orderData['service_id'], $orderData['link'], (int) $orderData['quantity'], $apiService);
        }
    }

    private function processTextareaOrders(OneDgApiService $apiService, array $orderLines): void
    {
        foreach ($orderLines as $line) {
            if (empty(trim($line))) {
                continue;
            }
            $parts = array_map('trim', explode('|', $line));
            if (count($parts) < 3) {
                $this->results[] = ['line' => $line, 'status' => 'error', 'message' => 'Định dạng không hợp lệ.'];

                continue;
            }
            [$serviceId, $link, $quantity] = $parts;
            $this->sendOrderRequest($line, $serviceId, $link, (int) $quantity, $apiService);
        }
    }

    private function sendOrderRequest(string $identifier, $serviceId, $link, $quantity, OneDgApiService $apiService): void
    {
        try {
            $response = $apiService->addOrder($serviceId, $link, $quantity);
            if (isset($response['order'])) {
                $this->results[] = ['line' => $identifier, 'status' => 'success', 'message' => 'Order ID: '.$response['order']];
            } else {
                $this->results[] = ['line' => $identifier, 'status' => 'error', 'message' => $response['error'] ?? 'Lỗi không xác định.'];
            }
        } catch (\Exception $e) {
            Log::error('Mass order API call failed: '.$e->getMessage());
            $this->results[] = ['line' => $identifier, 'status' => 'error', 'message' => 'Lỗi hệ thống khi gửi yêu cầu.'];
        }
    }
}
