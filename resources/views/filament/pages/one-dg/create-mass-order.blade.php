<x-filament-panels::page>
    {{-- Input Mode Toggle --}}
    <div class="flex justify-end mb-6">
        <div class="inline-flex p-1 space-x-1 bg-gray-100 rounded-lg dark:bg-gray-900" role="group">
            <button type="button" wire:click="$set('inputMode', 'repeater')"
                class="px-4 py-2 text-sm font-medium transition-colors duration-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800
                           {{ $inputMode === 'repeater' ? 'bg-white text-gray-900 shadow dark:bg-gray-700 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white' }}">
                Nhập chi tiết
            </button>
            <button type="button" wire:click="$set('inputMode', 'textarea')"
                class="px-4 py-2 text-sm font-medium transition-colors duration-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800
                           {{ $inputMode === 'textarea' ? 'bg-white text-gray-900 shadow dark:bg-gray-700 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white' }}">
                Nhập nhanh (Textarea)
            </button>
        </div>
    </div>

    <form wire:submit="submitMassOrder">
        {{-- Repeater Form --}}
        <div x-show="$wire.inputMode === 'repeater'" x-cloak x-transition>
            {{ $this->repeaterForm }}
        </div>

        {{-- Textarea Form --}}
        <div x-show="$wire.inputMode === 'textarea'" x-cloak x-transition>
            {{ $this->textareaForm }}
        </div>

        <div class="mt-6">
            <x-filament::button type="submit" icon="heroicon-m-rocket-launch">
                Gửi tất cả đơn hàng
            </x-filament::button>
        </div>
    </form>

    @if (!empty($results))
        <x-filament::section heading="Kết quả xử lý" class="mt-8">
            <div class="space-y-4">
                @foreach ($results as $result)
                    <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                        <div class="font-medium text-sm text-gray-600 dark:text-gray-300">
                            {{ $result['line'] }}
                        </div>
                        <div>
                            @if ($result['status'] === 'success')
                                <x-filament::badge color="success" icon="heroicon-m-check-circle">
                                    {{ $result['message'] }}
                                </x-filament::badge>
                            @else
                                <x-filament::badge color="danger" icon="heroicon-m-x-circle">
                                    {{ $result['message'] }}
                                </x-filament::badge>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
