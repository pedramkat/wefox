<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test_books_list_by_book_slug_returns_correct_books
     */
    public function test_books_list_returns_correct_books(): void
    {
        $book = Book::factory()->create();

        $response = $this->get('/api/v1/books');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $book->id]);
    }

    /**
     * test_books_price_is_shown_correctly
     */
    public function test_books_price_is_shown_correctly(): void
    {
        $book = Book::factory()->create([
            'price' => 888,
        ]);

        $response = $this->get('/api/v1/books');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'price' => 888,
            ]);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price' => 888]);
    }

    /**
     * test_books_list_returns_pagination
     */
    public function test_books_list_returns_pagination(): void
    {
        $booksPerPage = config('wefox.perPage');

        $book = Book::factory($booksPerPage + 1)->create();

        $response = $this->get('/api/v1/books/');

        $response->assertStatus(200);
        $response->assertJsonCount($booksPerPage, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    /**
     * test_books_list_sorts_by_starting_date_correctly
     */
    public function test_books_list_sorts_by_starting_date_correctly(): void
    {
        $firstBook = Book::factory()->create([
            'date_published' => now()->addDays(-1),
        ]);
        $secondBook = Book::factory()->create([
            'date_published' => now(),
        ]);

        $response = $this->get('/api/v1/books/');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.id', $firstBook->id);
        $response->assertJsonPath('data.1.id', $secondBook->id);
    }

    /**
     * test_books_list_sorts_by_price_correctly
     */
    public function test_books_list_sorts_by_price_correctly(): void
    {
        $cheapFirstBook = Book::factory()->create([
            'price' => 1000,
        ]);
        $cheapSecondBook = Book::factory()->create([
            'price' => 1100,
        ]);
        $ExpensiveBook = Book::factory()->create([
            'price' => 1899,
        ]);

        $response = $this->get('/api/v1/books/?sortBy=price&sortOrder=asc');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.id', $cheapFirstBook->id);
        $response->assertJsonPath('data.1.id', $cheapSecondBook->id);
        $response->assertJsonPath('data.2.id', $ExpensiveBook->id);
    }

    /**
     * test_books_list_returns_pagination
     */
    public function test_books_list_filter_by_price_correctly(): void
    {
        $cheapBook = Book::factory()->create([
            'price' => 1000,
        ]);
        $ExpensiveBook = Book::factory()->create([
            'price' => 1899,
        ]);

        $endpoint = '/api/v1/books/';

        $response = $this->get($endpoint.'?priceFrom=900');
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $cheapBook->id]);
        $response->assertJsonFragment(['id' => $ExpensiveBook->id]);

        $response = $this->get($endpoint.'?priceFrom=1200');
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $ExpensiveBook->id]);

        $response = $this->get($endpoint.'?priceFrom=1900');
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint.'?priceTo=1900');
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $cheapBook->id]);
        $response->assertJsonFragment(['id' => $ExpensiveBook->id]);

        $response = $this->get($endpoint.'?priceTo=1200');
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $cheapBook->id]);

        $response = $this->get($endpoint.'?priceTo=900');
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint.'?priceFrom=900&priceTo=1900');
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $cheapBook->id]);
        $response->assertJsonFragment(['id' => $ExpensiveBook->id]);
    }

    /**
     * test_books_list_filter_by_date_published_correctly
     */
    public function test_books_list_filter_by_publishedDate_correctly(): void
    {
        $firstBook = Book::factory()->create([
            'date_published' => now()->addDays(-1),
        ]);
        $secondBook = Book::factory()->create([
            'date_published' => now()->addDays(-3),
        ]);

        $endpoint = '/api/v1/books';

        $response = $this->get($endpoint.'?dateFrom='.now()->addDays(-4));
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $firstBook->id]);
        $response->assertJsonFragment(['id' => $secondBook->id]);

        $response = $this->get($endpoint.'?dateFrom='.now());
        $response->assertJsonCount(0, 'data');
        $response->assertJsonMissing(['id' => $firstBook->id]);
        $response->assertJsonMissing(['id' => $secondBook->id]);

        $response = $this->get($endpoint.'?dateFrom='.now()->addDays(4));
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint.'?dateTo='.now()->addDays(-2));
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $firstBook->id]);
        $response->assertJsonFragment(['id' => $secondBook->id]);

        $response = $this->get($endpoint.'?dateTo='.now());
        $response->assertJsonCount(2, 'data');

        $response = $this->get($endpoint.'?dateFrom='.now()->addDays(-2).'&dateTo='.now());
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $firstBook->id]);
        $response->assertJsonMissing(['id' => $secondBook->id]);
    }

    /**
     * test_books_list_returns_pagination
     */
    public function test_books_list_returns_validation_errors(): void
    {
        $endpoint = '/api/v1/books';

        $response = $this->getJson($endpoint.'?dateFrom=asd');
        $response->assertStatus(422);

        $response = $this->getJson($endpoint.'?priceFrom=asd');
        $response->assertStatus(422);

    }
}
