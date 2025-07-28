<x-filament-panels::page>
    <x-filament::section heading="Danh sách sản phẩm">
        <div class="overflow-x-auto">
            <x-filament-tables::table>
                <x-slot name="header">
                    <x-filament-tables::header-cell>ID</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell>Tên Sản phẩm</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell>Loại</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell>Giá</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell>Tồn kho</x-filament-tables::header-cell>
                    <x-filament-tables::header-cell>Trạng thái</x-filament-tables::header-cell>
                </x-slot>
                @forelse($products as $product)
                    <x-filament-tables::row>
                        <x-filament-tables::cell>{{ $product['product'] }}</x-filament-tables::cell>
                        <x-filament-tables::cell class="font-semibold">{{ $product['name'] }}</x-filament-tables::cell>
                        <x-filament-tables::cell>
                            <x-filament::badge color="gray">{{ $product['category'] }}</x-filament::badge>
                        </x-filament-tables::cell>
                        <x-filament-tables::cell>{{ $product['rate'] }}</x-filament-tables::cell>
                        <x-filament-tables::cell>{{ $product['inventory'] ?? 'N/A' }}</x-filament-tables::cell>
                        <x-filament-tables::cell>
                            @if ($product['status'] === 'In stock')
                                <x-filament::badge color="success">Còn hàng</x-filament::badge>
                            @else
                                <x-filament::badge color="danger">Hết hàng</x-filament::badge>
                            @endif
                        </x-filament-tables::cell>
                    </x-filament-tables::row>
                @empty
                    <x-filament-tables::row>
                        <x-filament-tables::cell colspan="6" class="text-center">
                            Không tìm thấy sản phẩm nào.
                        </x-filament-tables::cell>
                    </x-filament-tables::row>
                @endforelse
            </x-filament-tables::table>
        </div>
    </x-filament::section>

    <div class="grid grid-cols-1 gap-8 mt-8 lg:grid-cols-2">
        <x-filament::section heading="Tạo đơn hàng sản phẩm">
            <form wire:submit.prevent="createProductOrder">
                {{ $this->createForm }}
                <x-filament::button type="submit" class="mt-6">Tạo đơn hàng</x-filament::button>
            </form>
        </x-filament::section>

        <x-filament::section heading="Kiểm tra trạng thái đơn hàng sản phẩm">
            <form wire:submit.prevent="checkStatus">
                {{ $this->statusForm }}
                <x-filament::button type="submit" class="mt-6">Kiểm tra</x-filament::button>
            </form>

            @if ($statusResult)
                <div class="mt-6 space-y-4">
                    <h3 class="text-lg font-semibold">Kết quả:</h3>
                    @foreach ($statusResult as $id => $result)
                        <div class="p-4 border rounded-lg dark:border-gray-700">
                            <p class="font-bold">Order ID: {{ $id }}</p>
                            @if (isset($result['error']))
                                <x-filament::badge color="danger">{{ $result['error'] }}</x-filament::badge>
                            @else
                                <div class="mt-2 text-sm space-y-1">
                                    <p>Trạng thái: <x-filament::badge
                                            color="success">{{ $result['status'] }}</x-filament::badge></p>
                                    <p>Phí: <span class="font-mono">{{ $result['charge'] ?? 'N/A' }}</span></p>
                                    <p>Kết quả: <span class="font-mono text-xs">{{ $result['result'] ?? 'N/A' }}</span>
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
