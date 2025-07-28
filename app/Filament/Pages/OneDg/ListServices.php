<?php

// app/Filament/Pages/OneDg/ListServices.php

namespace App\Filament\Pages\OneDg;

use App\Services\OneDgApiService;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class ListServices extends Page
{
    use HasPageShield;
    use WithPagination; // Use Livewire's pagination trait

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static string $view = 'filament.pages.one-dg.list-services';

    protected static ?string $navigationGroup = 'OneDg Api Management';

    protected static ?string $navigationLabel = 'Danh sách Dịch vụ';

    protected static ?int $navigationSort = 1;

    // Data properties
    public Collection $allServices;

    public Collection $platforms;

    public Collection $categories;

    // Filter properties
    public ?string $keyword = '';

    public ?string $platform = null;

    public ?string $category = null;

    // Modal properties
    public bool $isDescriptionModalVisible = false;

    public ?array $viewingService = null;

    public function mount(OneDgApiService $apiService): void
    {
        $allServices = cache()->remember('onedg_services_list', now()->addMinutes(10), function () use ($apiService) {
            return $apiService->getServices() ?? [];
        });

        $this->allServices = collect($allServices);
        $this->platforms = $this->allServices->pluck('platform', 'platform')->unique()->filter()->sort();
        $this->updateCategories();
    }

    // Livewire hook to re-filter when properties change
    public function updated($property): void
    {
        // Reset pagination to the first page whenever a filter changes
        if (in_array($property, ['keyword', 'platform', 'category'])) {
            $this->resetPage();
        }

        if ($property === 'platform') {
            $this->category = null; // Reset category when platform changes
            $this->updateCategories();
        }
    }

    public function updateCategories(): void
    {
        $this->categories = $this->platform
            ? $this->allServices->where('platform', $this->platform)->pluck('category', 'category')->unique()->sort()
            : $this->allServices->pluck('category', 'category')->unique()->sort();
    }

    public function resetFilters(): void
    {
        $this->reset(['keyword', 'platform', 'category']);
        $this->updateCategories();
        $this->resetPage();
    }

    // A computed property to get the filtered and paginated services for the view
    #[Computed]
    public function services(): LengthAwarePaginator
    {
        $filtered = $this->allServices
            ->when($this->keyword, fn (Collection $c) => $c->filter(fn ($s) => str_contains(strtolower($s['name']), strtolower($this->keyword)) || $s['service'] == $this->keyword
            ))
            ->when($this->platform, fn (Collection $c) => $c->where('platform', $this->platform))
            ->when($this->category, fn (Collection $c) => $c->where('category', $this->category));

        // Manually paginate the filtered collection
        return new LengthAwarePaginator(
            items: $filtered->forPage($this->getPage(), 25),
            total: $filtered->count(),
            perPage: 25,
            currentPage: $this->getPage(),
            options: ['path' => $this->getUrl()]
        );
    }

    // Action to open the description modal
    public function viewDescription(int $serviceId): void
    {
        $this->viewingService = $this->allServices->firstWhere('service', $serviceId);
        $this->isDescriptionModalVisible = true;
    }
}
