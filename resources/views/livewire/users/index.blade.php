<?php

use App\Models\User;
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

    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public int $country_id = 0;

    // Delete action
    public function delete(User $user): void
    {
        $user->delete();
        $this->warning("$user->user_name deleted", 'Good bye!', position: 'toast-bottom');
    }

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'profile_picture', 'label' => '', 'class' => 'w-1'],
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'user_name', 'label' => 'Name', 'class' => 'w-64'],
            ['key' => 'email', 'label' => 'E-mail', 'sortable' => false],
        ];
    }

    public function users(): LengthAwarePaginator
    {
        return User::query()
            ->when($this->search, fn(Builder $q) => $q->where('user_name', 'like', "%$this->search%"))->orWhere('email', 'like', "%$this->search%")->role('admin')
            ->orderBy(...array_values($this->sortBy))
            ->paginate(10);
    }

    public function with(): array
    {
        return [
            'users' => $this->users(),
            'headers' => $this->headers(),
        ];
    }
}; ?>

<div>
    <!-- HEADER -->
    <x-header title="Users" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass"/>
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Create" link="/users/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" with-pagination link="users/{id}/edit" show-empty-text>
            @scope('cell_profile_picture', $user)
            <x-avatar image="{{ $user->profile_picture ?? '/empty-user.jpg' }}" class="!w-10" />
            @endscope
            @scope('actions', $user)
            <x-button icon="o-trash" wire:click="delete({{ $user['id'] }})" wire:confirm="Are you sure?" spinner
                      class="text-red-500 btn-ghost btn-sm"/>
            @endscope
            <x-slot:empty>
                <x-icon name="o-cube" label="It is empty." />
            </x-slot:empty>
        </x-table>
    </x-card>
</div>
