<?php

use App\Livewire\Forms\UserForm;
use App\Models\Country;
use App\Models\Language;
use App\Models\User;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

new class extends Component {
    use Toast, WithFileUploads;

    public UserForm $form;

    public function save(): void
    {
        // Create
        $this->form->store();

        // You can toast and redirect to any route
        $this->success('User created with success.', redirectTo: '/users');
    }

    // We also need this to fill Countries combobox on upcoming form
    public function with(): array
    {
        return [
            'countries' => Country::all(),
            'languages' => Language::all(), // Available Languages
        ];
    }
}; ?>

<div>
    <x-header title="Create user" separator/>

    <x-form wire:submit="save">
        {{--  Basic section  --}}
        <div class="lg:grid grid-cols-5">
            <div class="col-span-2">
                <x-header title="Basic" subtitle="Basic info from user" size="text-2xl" />
            </div>
            <div class="col-span-3 grid gap-3">
                <x-file label="Avatar" wire:model="form.photo" accept="image/png, image/jpeg" crop-after-change>
                    <img src="/empty-user.jpg" class="h-40 rounded-lg"/>
                </x-file>

                <x-input label="Name" wire:model="form.name"/>
                <x-input label="Email" wire:model="form.email"/>
                <x-select label="Country" wire:model="form.country_id" :options="$countries" placeholder="---"/>
            </div>
        </div>

        {{--  Details section --}}
        <hr class="my-5" />

        <div class="lg:grid grid-cols-5">
            <div class="col-span-2">
                <x-header title="Details" subtitle="More about the user" size="text-2xl" />
            </div>
            <div class="col-span-3 grid gap-3">
                {{-- Multi selection --}}
                <x-choices-offline
                    label="My languages"
                    wire:model="form.my_languages"
                    :options="$languages"
                    searchable />

                <x-editor wire:model="form.bio" label="Bio" hint="The great biography" />
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Cancel" link="/users"/>
            {{-- The important thing here is `type="submit"` --}}
            {{-- The spinner property is nice! --}}
            <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary"/>
        </x-slot:actions>
    </x-form>
</div>
