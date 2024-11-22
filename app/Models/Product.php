<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Accessor: Map `photo` to `product_photo`
    public function getProductPhotoAttribute()
    {
        return $this->attributes['photo'];
    }

    // Mutator: Map `product_photo` to `photo`
    public function setProductPhotoAttribute($value)
    {
        $this->attributes['photo'] = $value;
    }
}
