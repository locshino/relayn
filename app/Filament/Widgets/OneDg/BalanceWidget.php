<?php

// app/Filament/Widgets/OneDg/BalanceWidget.php

namespace App\Filament\Widgets\OneDg;

use App\Services\OneDgApiService;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Widget;

class BalanceWidget extends Widget
{
    use HasWidgetShield;

    protected static string $view = 'filament.widgets.one-dg.balance-widget';

    // The widget will span the full width of the content area.
    protected int|string|array $columnSpan = 'full';

    public ?array $balanceData = [];

    public ?string $error = null;

    public function mount(OneDgApiService $apiService): void
    {
        // Fetch balance information from the API
        $balanceResponse = $apiService->getBalance();

        if (isset($balanceResponse['balance'])) {
            $this->balanceData = $balanceResponse;
        } else {
            $this->error = $balanceResponse['error'] ?? 'Could not fetch balance information.';
        }
    }
}
