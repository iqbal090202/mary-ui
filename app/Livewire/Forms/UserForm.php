<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user;

    #[Validate('required')]
    public string $name = '';

    #[Validate('required|email')]
    public string $email = '';

    // Optional
    #[Validate('sometimes')]
    public ?int $country_id = null;

    #[Validate('nullable|image|max:1024')]
    public $photo;

    public function mount(): void
    {
        $this->fill($this->user);
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->country_id = $user->country_id;
    }

    public function update(): void
    {
        // Validate
        $data = $this->validate();

        // Update
        $this->user->update($data);

        // Upload file and save the avatar `url` on User model
        if ($this->photo) {
            $url = $this->photo->store('users', 'public');
            $this->user->update(['avatar' => "/storage/$url"]);
        }
    }
}
