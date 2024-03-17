<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test_book_update_api_is_not_accessed_by_public_user
     */
    public function test_book_update_api_is_not_accessed_by_public_user(): void
    {
        $book = Book::factory()->create();
        // public user
        $response = $this->putJson('/api/v1/book/update/'.$book->sku, [
            'name' => 'test book',
            'sku' => 'test-book',
            'description' => 'content of the first book',
        ]);
        $response->assertStatus(401);
    }

    /**
     * test_book_store_api_is_accessed_by_admin_user
     */
    public function test_book_update_api_is_accessed_by_authenticated_user(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $response = $this->actingAs($user)->putJson('/api/v1/book/update/'.$book->sku, [
            'name' => 'test book',
            'sku' => 'test-book',
            'description' => 'content of the first book',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'test book');
    }
}
