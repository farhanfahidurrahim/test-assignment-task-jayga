<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute')
            ->withPivot('value');
    }

    public function toSearchableArray()
    {
        $category = $this->category()->first();
        return [
            'name' => $this->name,
            'category' => $category ? $category->name : null,
            'price' => $this->price,
        ];
    }
}
