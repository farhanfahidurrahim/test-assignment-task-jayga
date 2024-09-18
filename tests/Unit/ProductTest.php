<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_product_belongs_to_a_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    /** @test */
    public function a_product_can_have_many_attributes()
    {
        $product = Product::factory()->create();
        $attribute1 = Attribute::factory()->create();
        $attribute2 = Attribute::factory()->create();

        $product->attributes()->attach($attribute1, ['value' => 'Value 1']);
        $product->attributes()->attach($attribute2, ['value' => 'Value 2']);

        $this->assertTrue($product->attributes->contains($attribute1));
        $this->assertTrue($product->attributes->contains($attribute2));
    }

    /** @test */
    public function a_product_can_be_created_with_valid_data()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 100,
            'category_id' => $category->id
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 100
        ]);
    }
}
