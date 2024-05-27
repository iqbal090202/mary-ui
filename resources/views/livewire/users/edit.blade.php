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
            'countries' => Country::all(),
            'languages' => Language::all(), // Available Languages
        ];
    }
}; ?>

<div>
    <x-header title="Update {{ $form->user->name }}" separator/>

    <div class="grid gap-5 lg:grid-cols-2">
        <div>
            <x-form wire:submit="save">
                <x-file label="Avatar" wire:model="form.photo" accept="image/png, image/jpeg" crop-after-change>
                    <img src="{{ $form->user->avatar ?? '/empty-user.jpg' }}" class="h-40 rounded-lg"/>
                </x-file>

                <x-input label="Name" wire:model="form.name"/>
                <x-input label="Email" wire:model="form.email"/>
                <x-select label="Country" wire:model="form.country_id" :options="$countries" placeholder="---"/>

                {{-- Multi selection --}}
                <x-choices-offline
                    label="My languages"
                    wire:model="form.my_languages"
                    :options="$languages"
                    searchable />

                <x-editor wire:model="form.bio" label="Bio" hint="The great biography" />

                <x-slot:actions>
                    <x-button label="Cancel" link="/users"/>
                    {{-- The important thing here is `type="submit"` --}}
                    {{-- The spinner property is nice! --}}
                    <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary"/>
                </x-slot:actions>
            </x-form>
        </div>
        <div>
            {{-- Get a nice picture from `StorySet` web site --}}
            <img src="/edit-form.png" width="300" class="mx-auto"/>
        </div>
    </div>
</div>
