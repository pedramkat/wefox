<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test_book_store_api_is_not_accessed_by_public_user
     */
    public function test_book_store_api_is_not_accessed_by_public_user(): void
    {
        // public user
        $response = $this->postJson('/api/v1/book/create', [
            'sky' => 'test-book',
            'name' => 'test book',
            'price' => 100,
            'description' => 'content of the first book',
            'author' => 'author of the first book',
            'date_published' => '2021-01-01',
        ]);
        $response->assertStatus(401);
    }

    /**
     * test_book_store_api_is_accessed_by_admin_user
     */
    public function test_book_store_api_is_accessed_by_created_user(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/v1/book/create', [
            'sku' => 'test-book',
            'name' => 'test book',
            'price' => 100,
            'description' => 'content of the first book',
            'author' => 'author of the first book',
            'date_published' => '2021-01-01',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.name', 'test book');
    }
}
