<x-filament-panels::page>
    <div class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            {{-- Keyword Filter --}}
            <div class="md:col-span-1">
                <label for="keyword_filter" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tìm
                    kiếm</label>
                <input type="search" id="keyword_filter" wire:model.live.debounce.500ms="keyword"
                    placeholder="Từ khóa hoặc ID..."
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            </div>

            {{-- Platform Filter --}}
            <div class="md:col-span-1">
                <label for="platform_filter" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nền
                    tảng</label>
                <select id="platform_filter" wire:model.live="platform"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option value="">Tất cả nền tảng</option>
                    @foreach($platforms as $platformValue)
                        <option value="{{ $platformValue }}">{{ $platformValue }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Category Filter --}}
            <div class="md:col-span-1">
                <label for="category_filter" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Danh
                    mục</label>
                <select id="category_filter" wire:model.live="category"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $categoryValue)
                        <option value="{{ $categoryValue }}">{{ $categoryValue }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Reset Button --}}
            <div class="flex items-end md:col-span-1">
                <button type="button" wire:click="resetFilters"
                    class="w-full px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Đặt lại
                </button>
            </div>
        </div>
    </div>

    <div class="mt-6 overflow-hidden bg-white rounded-lg shadow-md dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-[8%]">ID</th>
                        <th scope="col" class="px-6 py-3 w-[52%]">Tên Dịch vụ</th>
                        <th scope="col" class="px-6 py-3 w-[15%]">Giá / 1k</th>
                        <th scope="col" class="px-6 py-3 w-[15%] text-center">Min / Max</th>
                        <th scope="col" class="px-6 py-3 w-[10%] text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->services() as $service)
                        <tr wire:key="service-{{ $service['service'] }}"
                            class="border-b dark:border-gray-700 dark:hover:bg-gray-900">
                            <td class="px-6 py-4 font-mono text-gray-900 dark:text-white">{{ $service['service'] }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $service['name'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $service['category'] }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-primary-600 dark:text-primary-400">
                                ${{ number_format((float) $service['rate'], 3) }}</td>
                            <td class="px-6 py-4 text-center">{{ $service['min'] }} / {{ $service['max'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <button type="button" wire:click="viewDescription({{ $service['service'] }})"
                                    class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <svg class="w-5 h-5" aria-hidden="true"
                                        xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none"
                                        viewBox="0 0 20 14">
                                        <g stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2">
                                            <path d="M10 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                            <path d="M10 13c4.97 0 9-2.686 9-6s-4.03-6-9-6-9 2.686-9 6 4.03 6 9 6Z" />
                                        </g>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" aria-hidden="true"
                                        xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none"
                                        viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <p class="text-lg font-medium">Không tìm thấy dịch vụ nào.</p>
                                    <p class="text-sm">Hãy thử thay đổi bộ lọc của bạn.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $this->services()->links() }}
        </div>
    </div>

    {{-- Description Modal --}}
    <div x-data="{ show: $wire.entangle('isDescriptionModalVisible') }" x-show="show"
        x-on:keydown.escape.window="show = false" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">

        {{-- Backdrop --}}
        <div x-show="show" @click="show = false" x-transition.opacity class="fixed inset-0 bg-gray-900/50"></div>

        {{-- Modal Panel --}}
        <div x-show="show" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-2xl p-6 mx-4 bg-white rounded-lg shadow-xl dark:bg-gray-800">

            @if ($viewingService)
                <div class="flex items-start justify-between pb-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $viewingService['name'] }}
                    </h3>
                    <button type="button" @click="show = false"
                        class="inline-flex items-center justify-center w-8 h-8 ml-auto text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true"
                            xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="py-6 space-y-6">
                    <div class="prose dark:prose-invert max-w-none">
                        {!! $viewingService['desc'] ?? 'Không có mô tả.' !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>