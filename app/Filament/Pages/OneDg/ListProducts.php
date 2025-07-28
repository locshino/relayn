<?php

// app/Filament/Pages/OneDg/ListProducts.php

namespace App\Filament\Pages\OneDg;

use App\Services\OneDgApiService;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class ListProducts extends Page
{
    use HasPageShield;
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string $view = 'filament.pages.one-dg.list-products';

    protected static ?string $navigationGroup = 'OneDg Api Management';

    protected static ?string $navigationLabel = 'Danh sách Sản phẩm';

    protected static ?int $navigationSort = 3;

    public Collection $allProducts;

    public Collection $categories;

    public ?string $keyword = '';

    public ?string $category = null;

    public function mount(OneDgApiService $apiService): void
    {
        $allProducts = cache()->remember('onedg_products_list', now()->addMinutes(10), function () use ($apiService) {
            return $apiService->getProducts() ?? [];
        });

        $this->allProducts = collect($allProducts);
        $this->categories = $this->allProducts->pluck('category', 'category')->unique()->sort();
    }

    public function updated($property): void
    {
        if (in_array($property, ['keyword', 'category'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['keyword', 'category']);
        $this->resetPage();
    }

    #[Computed]
    public function products(): LengthAwarePaginator
    {
        $filtered = $this->allProducts
            ->when($this->keyword, fn (Collection $c) => $c->filter(fn ($p) => str_contains(strtolower($p['name']), strtolower($this->keyword)) || $p['product'] == $this->keyword
            ))
            ->when($this->category, fn (Collection $c) => $c->where('category', $this->category));

        return new LengthAwarePaginator(
            items: $filtered->forPage($this->getPage(), 25),
            total: $filtered->count(),
            perPage: 25,
            currentPage: $this->getPage(),
            options: ['path' => $this->getUrl()]
        );
    }
}
