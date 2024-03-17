<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * Test if the API returns the correct price for a book.
     */
    public function test_show_book_api_returns_currect_price(): void
    {
        $book = Book::factory()->create([
            'price' => 888,
        ]);

        $response = $this->get('/api/v1/book/'.$book->sku);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'price' => 888,
                ],
            ]);
    }
}
