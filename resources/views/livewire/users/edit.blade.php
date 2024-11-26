<?php

use App\Livewire\Forms\UserForm;
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
}; ?>

<div>
    <x-header title="Update {{ $form->user->user_name }}" separator/>

    <x-form wire:submit="save">
        {{--  Basic section  --}}
        <div class="grid-cols-5 lg:grid">
            <div class="col-span-2">
                <x-header title="Basic" subtitle="Basic info from user" size="text-2xl" />
            </div>
            <div class="grid col-span-3 gap-3">
                <x-file label="Profile Picture" wire:model="form.photo" accept="image/png, image/jpeg" crop-after-change>
                    <img src="{{ $form->user->photo ?? '/empty-user.jpg' }}" class="h-40 rounded-lg"/>
                </x-file>

                <x-input label="Name" wire:model="form.user_name"/>
                <x-input label="Email" wire:model="form.email"/>
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
