<x-filament-widgets::widget>
    <x-filament::section>
        @if ($error)
            <div class="p-4 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-gray-800 dark:text-red-400">
                <strong>Lỗi:</strong> {{ $error }}
            </div>
        @elseif(!empty($balanceData))
            <div class="flex items-center p-4">
                <div class="flex-shrink-0 p-3 text-primary-500 bg-primary-500/10 rounded-full">
                    <x-heroicon-o-banknotes class="w-8 h-8" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Số dư tài khoản 1DG
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $balanceData['balance'] }}
                        <span
                            class="text-lg font-medium text-gray-600 dark:text-gray-300">{{ $balanceData['currency'] }}</span>
                    </p>
                </div>
            </div>
        @else
            <div class="p-4 text-center text-gray-500">
                Đang tải số dư...
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
