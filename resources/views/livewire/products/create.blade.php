<?php

use App\Livewire\Forms\ProductForm;
use App\Models\Product;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

new class extends Component {
    use Toast, WithFileUploads;

    public ProductForm $form;

    public function save(): void
    {
        $this->form->store();

        $this->success('Product created with success.', redirectTo: '/products');
    }

    public function addVariant(): void
    {
        $this->form->addVariant();

        $this->success('Variant added with success.');
    }

    public function removeVariant($index): void
    {
        $this->form->removeVariant($index);

        $this->success('Variant removed with success.');
    }
}; ?>

<div>
    <x-header title="Create product" separator/>

    <x-form wire:submit="save">
        <div class="grid-cols-5 lg:grid">
            <div class="col-span-2">
                <x-header title="Basic" subtitle="Basic info from product" size="text-2xl" />
            </div>
            <div class="grid col-span-3 gap-3">
                <x-file label="Photo" wire:model="form.photo" accept="image/png, image/jpeg" crop-after-change>
                    <img src="/empty-user.jpg" class="h-40 rounded-lg"/>
                </x-file>

                <x-input label="Product Name" wire:model="form.product_name"/>
                <x-input label="Price" wire:model="form.price" prefix="Rp" money locale="id-ID"/>
                <x-input label="Stock" wire:model="form.stock"/>
                <x-input label="Discount" suffix="%" wire:model="form.discount"/>
                <x-textarea label="Description" wire:model="form.description" rows="5" />
            </div>
        </div>

        <hr class="my-5" />

        {{--  Variant section --}}
        <div class="grid-cols-5 lg:grid">
            <div class="col-span-2">
                <x-header title="Variants" subtitle="More about the product" size="text-2xl" />
            </div>
            <div class="grid col-span-3 gap-3">
                <x-button label="Add Variant" icon="o-plus" wire:click="addVariant" class="btn-primary"/>

                @foreach ($form->variants as $index => $variant)
                    <hr class="my-3" />
                    <div class="grid gap-3 mb-3">
                        <x-input label="Variant Name" wire:model="form.variants.{{$index}}.variant_name "/>
                        <x-input label="Price" wire:model="form.variants.{{$index}}.price" prefix="Rp" money locale="id-ID"/>
                        <x-input label="Stock" wire:model="form.variants.{{$index}}.stock"/>
                    </div>
                    <x-button label="Remove" icon="o-trash" wire:click="removeVariant({{$index}})" class="btn-error"/>
                @endforeach
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Cancel" link="/products"/>
            <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary"/>
        </x-slot:actions>
    </x-form>
</div>
