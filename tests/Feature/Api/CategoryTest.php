<?php

namespace Tests\Feature\Api;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    private $endpoint = '/categories';

    public function test_it_can_list_categories()
    {
        Category::factory()->count(6)->create();

        $response = $this->getJson($this->endpoint);

        $response
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ["id", "title", "slug", "description", "date_created"]
                ]
            ])
            ->assertOk();
    }

    public function test_it_can_show_a_category()
    {
        $category = new CategoryResource(Category::factory()->create());

        $response = $this->getJson("{$this->endpoint}/{$category->url}");

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => ["id", "title", "slug", "description", "date_created"]
            ]);
    }

    public function test_it_cant_show_a_nonexistent_category()
    {
        $response = $this->getJson("{$this->endpoint}/any-id");

        $response
            ->assertNotFound();
    }

    public function test_it_cant_create_a_category_without_required_fields()
    {
        $response = $this->postJson($this->endpoint, [
            'title' => null,
            'description' => null
        ]);

        $response
            ->assertStatus(422);
    }

    public function test_it_can_create_a_category()
    {
        $response = $this->postJson($this->endpoint, [
            'title' => 'Category 01',
            'description' => 'Description of Category 01',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => ["id", "title", "slug", "description", "date_created"]
            ])
            ->assertJsonFragment([
                'id' => 1,
                'title' => 'Category 01',
                'description' => 'Description of Category 01',
                'slug' => 'category-01'
            ]);

        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseHas('categories', [
            'id' => 1,
            'title' => 'Category 01',
            'description' => 'Description of Category 01',
            'url' => 'category-01'
        ]);
    }

    public function test_it_cant_update_a_nonexistent_category()
    {
        Category::factory()->create([
            'title' => 'Category 01',
            'description' => 'Description of Category 01',
        ]);

        $response = $this->putJson("{$this->endpoint}/fake-url", [
            'title' => 'Category 02',
            'description' => 'Description of Category 02',
        ]);

        $response
            ->assertNotFound();

        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseHas('categories', [
            'id' => 1,
            'title' => 'Category 01',
            'description' => 'Description of Category 01',
            'url' => 'category-01'
        ]);
    }

    public function test_it_cant_update_a_category_without_required_fields()
    {
        $category = Category::factory()->create([
            'title' => 'Category 01',
            'description' => 'Description of Category 01',
        ]);

        $response = $this->putJson("{$this->endpoint}/{$category->url}", [
            'title' => null,
            'description' => null,
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title', 'description'
            ]);

        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseHas('categories', [
            'id' => 1,
            'title' => 'Category 01',
            'description' => 'Description of Category 01',
            'url' => 'category-01'
        ]);
    }

    public function test_it_can_update_a_category()
    {
        $category = new CategoryResource(Category::factory()->create([
            'title' => 'Category 01',
            'description' => 'Description of Category 01',
        ]));

        $response = $this->putJson("{$this->endpoint}/{$category->url}", [
            'title' => 'Category 02',
            'description' => 'Description of Category 02',
        ]);

        $response
            ->assertOk()
            ->assertJsonMissingValidationErrors('data')
            ->assertJsonStructure([
                'data' => ["id", "title", "slug", "description", "date_created"]
            ])
            ->assertJsonFragment([
                'id' => 1,
                'title' => 'Category 02',
                'description' => 'Description of Category 02',
                'slug' => 'category-02'
            ]);

        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseHas('categories', [
            'id' => 1,
            'title' => 'Category 02',
            'description' => 'Description of Category 02',
            'url' => 'category-02'
        ]);
    }

    public function test_it_cant_delete_a_nonexistent_category()
    {
        Category::factory()->create([
            'title' => 'Category 01',
            'description' => 'Description of Category 01',
        ]);

        $response = $this->deleteJson("{$this->endpoint}/fake-url");

        $response
            ->assertNotFound();

        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseHas('categories', [
            'id' => 1,
            'title' => 'Category 01',
            'description' => 'Description of Category 01',
            'url' => 'category-01'
        ]);
    }

    public function test_it_can_delete_a_category()
    {
        $category = Category::factory()->create([
            'title' => 'Category 01',
            'description' => 'Description of Category 01',
        ]);

        $response = $this->deleteJson("{$this->endpoint}/{$category->url}");

        $response
            ->assertNoContent();

        $this->assertDatabaseCount('categories', 0);
        $this->assertDatabaseMissing('categories', [
            'id' => 1,
            'title' => 'Category 01',
            'description' => 'Description of Category 01',
            'url' => 'category-01'
        ]);
    }
}
