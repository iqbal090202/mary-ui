<?php

use App\Livewire\Forms\UserForm;
use App\Models\Country;
use App\Models\User;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public User $user;

    public UserForm $form;

    public function mount(User $user): void
    {
        $this->form->setUser($user);
    }

    public function save(): void
    {
        // Update
        $this->form->update();

        // You can toast and redirect to any route
        $this->success('User updated with success.', redirectTo: '/users');
    }

    // We also need this to fill Countries combobox on upcoming form
    public function with(): array
    {
        return [
            'countries' => Country::all()
        ];
    }
}; ?>

<div>
    <x-header title="Update {{ $user->name }}" separator/>

    <x-form wire:submit="save">
        <x-input label="Name" wire:model="form.name"/>
        <x-input label="Email" wire:model="form.email"/>
        <x-select label="Country" wire:model="form.country_id" :options="$countries" placeholder="---"/>

        <x-slot:actions>
            <x-button label="Cancel" link="/users"/>
            {{-- The important thing here is `type="submit"` --}}
            {{-- The spinner property is nice! --}}
            <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary"/>
        </x-slot:actions>
    </x-form>
</div>