<x-filament-panels::page>
    <x-filament::tabs>
        <x-filament::tabs.item :active="$activeTab === 'create'" wire:click="$set('activeTab', 'create')" icon="heroicon-m-plus-circle">
            Tạo đơn hàng
        </x-filament::tabs.item>

        <x-filament::tabs.item :active="$activeTab === 'check_status'" wire:click="$set('activeTab', 'check_status')"
            icon="heroicon-m-magnifying-glass">
            Kiểm tra trạng thái
        </x-filament::tabs.item>
    </x-filament::tabs>

    <div class="mt-6">
        {{-- Create Order Tab Content --}}
        <div x-show="$wire.activeTab === 'create'" x-cloak>
            <form wire:submit="create">
                {{ $this->createForm }}

                <div class="mt-6">
                    <x-filament::button type="submit" icon="heroicon-o-shopping-cart">
                        Gửi đơn hàng
                    </x-filament::button>
                </div>
            </form>
        </div>

        {{-- Check Status Tab Content --}}
        <div x-show="$wire.activeTab === 'check_status'" x-cloak>
            <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
                <form wire:submit="checkStatuses">
                    {{ $this->checkStatusForm }}
                    <div class="mt-6">
                        <button type="submit"
                            class="flex items-center justify-center w-full px-5 py-3 text-base font-medium text-center text-white transition-colors duration-200 bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            <svg class="w-5 h-5 mr-2 -ml-1"
                                xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            Kiểm tra trạng thái
                        </button>
                    </div>
                </form>
            </div>

            @if ($orders !== null)
                <div class="mt-8">
                    @if (!empty($selectedOrders))
                        <div class="flex items-center p-4 mb-6 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-900 dark:text-blue-400"
                            role="alert">
                            <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true"
                                xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                            </svg>
                            <span class="font-medium">{{ count($selectedOrders) }} đơn hàng đã được chọn.</span>
                            <button type="button" wire:click="refillSelected"
                                class="ml-auto px-4 py-2 text-xs font-medium text-center text-white transition-colors duration-200 bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                                Refill các mục đã chọn
                            </button>
                        </div>
                    @endif

                    <div class="space-y-6">
                        @forelse ($orders as $orderId => $order)
                            <div wire:key="order-{{ $orderId }}"
                                class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                                    <div class="flex-grow">
                                        <div class="flex items-center mb-3">
                                            <input id="checkbox-{{ $orderId }}" type="checkbox"
                                                value="{{ $orderId }}"
                                                wire:model.live="selectedOrders.{{ $orderId }}"
                                                class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="checkbox-{{ $orderId }}"
                                                class="ml-3 text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $orderId }}</label>
                                        </div>
                                        @if (isset($order['error']))
                                            <p class="font-medium text-red-600 dark:text-red-400">{{ $order['error'] }}
                                            </p>
                                        @else
                                            <p class="font-semibold text-gray-700 dark:text-gray-300">Thông tin dịch vụ
                                                không có sẵn</p>
                                            <p class="mt-1 text-sm text-gray-500 truncate dark:text-gray-400"><span
                                                    class="font-medium">Link:</span> {{ $order['link'] ?? 'N/A' }}</p>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0 mt-4 md:mt-0 md:ml-6 md:w-80">
                                        @if (isset($order['status']))
                                            <div class="mb-4">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ ['success' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300', 'primary' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300', 'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300', 'danger' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300', 'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'][$this->getStatusColor($order['status'])] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $order['status'] }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                            <div class="text-gray-500">Phí:</div>
                                            <div class="font-semibold text-right text-gray-900 dark:text-white">
                                                ${{ number_format((float) ($order['charge'] ?? 0), 5) }}</div>
                                            <div class="text-gray-500">Số lượng:</div>
                                            <div class="font-semibold text-right text-gray-900 dark:text-white">
                                                {{ $order['quantity'] ?? 'N/A' }}</div>
                                            <div class="text-gray-500">Bắt đầu:</div>
                                            <div class="font-semibold text-right text-gray-900 dark:text-white">
                                                {{ $order['start_count'] ?? 'N/A' }}</div>
                                            <div class="text-gray-500">Còn lại:</div>
                                            <div class="font-semibold text-right text-gray-900 dark:text-white">
                                                {{ $order['remains'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-16 text-center text-gray-500 bg-white rounded-xl dark:bg-gray-800">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" aria-hidden="true"
                                    xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none"
                                    viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <p class="text-xl font-semibold">Không có dữ liệu</p>
                                <p class="mt-1 text-sm">Vui lòng nhập Order ID và nhấn nút kiểm tra.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
