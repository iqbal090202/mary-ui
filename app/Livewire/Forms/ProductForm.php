<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductForm extends Form
{
    public ?Product $product;

    #[Validate('required')]
    public string $product_name = '';

    #[Validate('nullable|image|max:2024')]
    public $product_photo;

    #[Validate('sometimes')]
    public ?string $description = null;

    #[Validate('required')]
    public int $price = 0;

    #[Validate('required')]
    public int $stock = 0;

    #[Validate('required|max:100')]
    public int $discount = 0;

    #[Validate('array')]
    public array $variants = [];

    public function setProduct(Product $product): void
    {
        $this->product = $product;

        $this->fill($this->product);

        $this->variants = $product->variants()->get()->toArray();
    }

    public function addVariant(): void
    {
        $this->variants[] = [
            'variant_name' => '',
            'price' => 0,
            'stock' => 0
        ];
    }

    public function removeVariant(int $index): void
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function store(): void
    {
        $this->validate();
        $this->product = Product::create($this->all());

        if ($this->product_photo) {
            $url = $this->product_photo->store('products', 'public');
            $this->product->update(['photo' => "/storage/$url"]);
        }

        foreach ($this->variants as $variant) {
            $this->product->variants()->create($variant);
        }
    }

    public function update(): void
    {
        $data = $this->validate();

        $this->product->update($data);

        if ($this->product_photo) {
            $url = $this->product_photo->store('products', 'public');
            $this->product->update(['photo' => "/storage/$url"]);
        }

        $existingVariantsIds = $this->product->variants()->pluck('id')->toArray();

        foreach ($this->variants as $variant) {
            if (isset($variant['id'])) {
                $this->product->variants()->find($variant['id'])->update($variant);
                $existingVariantsIds = array_diff($existingVariantsIds, [$variant['id']]);
            } else {
                $this->product->variants()->create($variant);
            }
        }

        $this->product->variants()->whereIn('id', $existingVariantsIds)->delete();
    }
}
