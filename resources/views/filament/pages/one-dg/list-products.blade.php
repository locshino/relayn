<x-filament-panels::page>
    <div class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="md:col-span-1">
                <x-filament::input.wrapper>
                    <x-filament::input type="search" wire:model.live.debounce.500ms="keyword"
                        placeholder="Tìm theo từ khóa hoặc ID..." />
                </x-filament::input.wrapper>
            </div>
            <div class="md:col-span-1">
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="category">
                        <option value="">Tất cả danh mục</option>
                        @foreach ($categories as $categoryValue)
                            <option value="{{ $categoryValue }}">{{ $categoryValue }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
            <div class="flex items-center md:col-span-1">
                <x-filament::button color="gray" wire:click="resetFilters">Đặt lại</x-filament::button>
            </div>
        </div>
    </div>

    <div class="mt-6 overflow-hidden bg-white rounded-lg shadow-md dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Tên Sản phẩm</th>
                        <th scope="col" class="px-6 py-3">Giá</th>
                        <th scope="col" class="px-6 py-3">Tồn kho</th>
                        <th scope="col" class="px-6 py-3">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->products() as $product)
                        <tr wire:key="product-{{ $product['product'] }}"
                            class="border-b dark:border-gray-700 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-mono">{{ $product['product'] }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $product['name'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $product['category'] }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-primary-600 dark:text-primary-400">
                                ${{ number_format((float) $product['rate'], 2) }}</td>
                            <td class="px-6 py-4">{{ $product['inventory'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @if ($product['status'] === 'In stock')
                                    <span
                                        class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">Còn
                                        hàng</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300">Hết
                                        hàng</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500">Không tìm thấy sản phẩm nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $this->products()->links() }}
        </div>
    </div>
</x-filament-panels::page>
