@if ($platforms->isNotEmpty())
    <div class="flex flex-wrap gap-2">
        @foreach ($platforms as $platform)
            <x-filament::button wire:click="selectPlatform('{{ $platform }}')" size="sm"
                color="{{ $this->selectedPlatform === $platform ? 'primary' : 'gray' }}" :class="$this->selectedPlatform === $platform
                    ? 'bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white hover:brightness-110'
                    : 'bg-gray-100 text-gray-900 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700'">
                {{ $platform }}
            </x-filament::button>
        @endforeach
    </div>
@endif
