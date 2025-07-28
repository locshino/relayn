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
        {{-- Create Order Tab --}}
        <div x-show="$wire.activeTab === 'create'" x-cloak>
            <x-filament::section>
                <form wire:submit.prevent="createProductOrder">
                    {{ $this->createForm }}
                    <x-filament::button type="submit" class="mt-6">Tạo đơn hàng</x-filament::button>
                </form>
            </x-filament::section>
        </div>

        {{-- Check Status Tab --}}
        <div x-show="$wire.activeTab === 'check_status'" x-cloak>
            <x-filament::section>
                <form wire:submit.prevent="checkStatus">
                    {{ $this->statusForm }}
                    <x-filament::button type="submit" class="mt-6">Kiểm tra</x-filament::button>
                </form>

                @if ($statusResult)
                    <div class="mt-6 space-y-4">
                        <h3 class="text-lg font-semibold dark:text-white">Kết quả:</h3>
                        @foreach ($statusResult as $id => $result)
                            <div class="p-4 border rounded-lg dark:border-gray-700">
                                <p class="font-bold dark:text-white">Order ID: {{ $id }}</p>
                                @if (isset($result['error']))
                                    <x-filament::badge color="danger">{{ $result['error'] }}</x-filament::badge>
                                @else
                                    <div class="mt-2 text-sm space-y-1">
                                        <p>Trạng thái: <x-filament::badge
                                                color="success">{{ $result['status'] }}</x-filament::badge></p>
                                        <p>Phí: <span class="font-mono">{{ $result['charge'] ?? 'N/A' }}</span></p>
                                        <p>Kết quả: <span
                                                class="font-mono text-xs">{{ $result['result'] ?? 'N/A' }}</span></p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
