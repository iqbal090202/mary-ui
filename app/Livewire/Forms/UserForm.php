<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user;

    #[Validate('required')]
    public string $user_name = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('nullable|image|max:2024')]
    public $photo;

    public string $password = '';

    public string $password_confirmation = '';

    public function setUser(User $user): void
    {
        $this->user = $user;

        $this->fill($this->user);
    }

    public function store(): void
    {
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];

        $validatedData = $this->validate([...$this->getRules(), ...$rules]);

        $this->user = User::create($validatedData);
        $this->user->assignRole('admin');

        if ($this->photo) {
            $url = $this->photo->store('users', 'public');
            $this->user->update(['profile_picture' => "/storage/$url"]);
        }
    }

    public function update(): void
    {
        $data = $this->validate();

        $this->user->update($data);

        if ($this->photo) {
            $url = $this->photo->store('users', 'public');
            $this->user->update(['profile_picture' => "/storage/$url"]);
        }
    }
}
