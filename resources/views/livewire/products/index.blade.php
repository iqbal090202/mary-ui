<?php

use App\Models\Product;
use App\Traits\ClearsFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use ClearsFilters, Toast, WithPagination;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'product_name', 'direction' => 'asc'];

    // Filter count
    public function filters()
    {
        $count = 0;

        if (!empty($this->search)) {
            $count++;
        }

        // if ($this->country_id > 0) {
        //     $count++;
        // }

        return $count;
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'product_name', 'label' => 'Product Name', 'class' => 'w-1'],
            ['key' => 'photo', 'label' => 'Photo', 'class' => 'w-1'],
            ['key' => 'price', 'label' => 'Price', 'class' => 'w-1'],
            ['key' => 'stock', 'label' => 'Stock', 'class' => 'w-1'],
        ];
    }

    public function products(): LengthAwarePaginator
    {
        return Product::query()
            ->when($this->search, fn(Builder $q) => $q->where('product_name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);
    }

    public function with(): array
    {
        return [
            'products' => $this->products(),
            'headers' => $this->headers(),
            'filters' => $this->filters(),
        ];
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="Products" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass"/>
        </x-slot:middle>
        <x-slot:actions>
            {{-- <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" :badge="$filters"/> --}}
            <x-button label="Create" link="/products/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$products" :sort-by="$sortBy" with-pagination link="products/{id}/edit">
            @scope('cell_id', $product)
            <strong>{{ $product->id }}</strong>
            @endscope
            @scope('cell_photo', $product)
            <img src="{{ $product->photo ?? '/empty-user.jpg' }}" class="rounded-lg h-30"/>
            @endscope
            @scope('actions', $product)
            <x-button icon="o-trash" wire:click="delete({{ $product['id'] }})" wire:confirm="Are you sure?" spinner
                      class="text-red-500 btn-ghost btn-sm"/>
            @endscope
            <x-slot:empty>
                <x-icon name="o-cube" label="It is empty." />
            </x-slot:empty>
        </x-table>
    </x-card>
</div>
