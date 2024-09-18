<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_product()
    {
        $category = Category::factory()->create();
        $attribute = Attribute::factory()->create();

        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'price' => 100,
            'category_id' => $category->id,
            'attributes' => [
                [
                    'id' => $attribute->id,
                    'value' => 'Value 1'
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Product Create Successfully!',
                'data' => [
                    'name' => 'Test Product',
                    'price' => 100,
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                    ],
                    'attributes' => [
                        [
                            'id' => $attribute->id,
                            'name' => $attribute->name,
                            'value' => 'Value 1',
                        ]
                    ],
                ]
            ]);
    }

    public function test_update_product()
    {
        $category = Category::factory()->create();
        $attribute = Attribute::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->putJson('/api/products/' . $product->id, [
            'name' => 'Updated Product',
            'price' => 150,
            'category_id' => $category->id,
            'attributes' => [
                [
                    'id' => $attribute->id,
                    'value' => 'Updated Value'
                ]
            ]
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product Updated Successfully!',
                'data' => [
                    'name' => 'Updated Product',
                    'price' => 150,
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                    ],
                    'attributes' => [
                        [
                            'id' => $attribute->id,
                            'name' => $attribute->name,
                            'value' => 'Updated Value',
                        ]
                    ],
                ]
            ]);
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson('/api/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product Deleted Successfully!'
            ]);
    }
}

