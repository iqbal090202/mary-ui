<?php

use App\Helpers\PriceFormatter;
use App\Models\Transaction;
use App\Traits\ClearsFilters;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use ClearsFilters, Toast, WithPagination;

    public array $expanded = [2];

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'updated_at', 'direction' => 'desc'];

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
            ['key' => 'user.user_name', 'label' => 'Name', 'class' => 'w-1'],
            ['key' => 'date', 'label' => 'Date', 'class' => 'w-1'],
            ['key' => 'total_price', 'label' => 'Total Price', 'class' => 'w-1'],
        ];
    }

    public function detailheaders(): array
    {
        return [
            ['key' => 'product.product_name', 'label' => 'Product Name', 'class' => 'w-1'],
            ['key' => 'price', 'label' => 'Price', 'class' => 'w-1'],
            ['key' => 'qty', 'label' => 'Quantity', 'class' => 'w-1'],
        ];
    }

    public function transactions(): LengthAwarePaginator
    {
        return Transaction::query()
            ->with('details.product', 'details.variant')
            // ->when($this->search, fn(Builder $q) => $q->where('product_name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);
    }

    public function with(): array
    {
        return [
            'transactions' => $this->transactions(),
            'headers' => $this->headers(),
            'filters' => $this->filters(),
            'detailheaders' => $this->detailheaders()
        ];
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="Transactions" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass"/>
        </x-slot:middle>
        <x-slot:actions>
            {{-- <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" :badge="$filters"/> --}}
            {{-- <x-button label="Create" link="/transactions/create" responsive icon="o-plus" class="btn-primary" /> --}}
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$transactions" :sort-by="$sortBy" wire:model="expanded" expandable with-pagination link="transactions/{id}/edit">
            @scope('cell_total_price', $transaction)
            <div>{{ PriceFormatter::format($transaction->total_price) }}</div>
            @endscope
            @scope('expansion', $transaction, $detailheaders)
                <x-table :headers="$detailheaders" :rows="$transaction->details">
                    @scope('cell_price', $detail)
                    <div>{{ PriceFormatter::format($detail->price) }}</div>
                    @endscope
                </x-table>
            @endscope
            <x-slot:empty>
                <x-icon name="o-cube" label="It is empty." />
            </x-slot:empty>
        </x-table>
    </x-card>
</div>
