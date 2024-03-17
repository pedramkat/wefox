<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/book/{book:sku}",
     *     summary="Get one book by it's sku",
     *     description="Retrieve a specific book based on the provided sku",
     *     tags={"Api v1 - Books"},
     *     security={{ "sanctum": {} }},
     *
     *     @OA\Parameter(
     *         name="book:sku",
     *         in="path",
     *         description="sku of the book to update",
     *         required=true,
     *         example="vuv-gox",
     *
     *         @OA\Schema(
     *             type="string",
     *             format="varchar",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Unauthorized"
     *             )
     *         )
     *     )
     * )
     */
    public function show(Book $book): mixed
    {
        if ($book->exists) {
            return new BookResource($book);
        }

        return response()->json(['message' => 'Book not found'], 404);
    }

    /**
     *  * @OA\Get(
     *     path="/api/v1/books",
     *     summary="Retrieve all books",
     *     description="Get a list of all books based on the provided filters",
     *     tags={"Api v1 - Books"},
     *
     *     @OA\Parameter(
     *         name="priceFrom",
     *         in="query",
     *         description="Minimum price for filtering books",
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="priceTo",
     *         in="query",
     *         description="Maximum price for filtering books",
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="dateFrom",
     *         in="query",
     *         description="Minimum starting date for filtering books",
     *
     *         @OA\Schema(
     *             type="string",
     *             format="date"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="dateTo",
     *         in="query",
     *         description="Maximum ending date for filtering books",
     *
     *         @OA\Schema(
     *             type="string",
     *             format="date"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         description="Field to sort the books by",
     *         example="price",
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="sortOrder",
     *         in="query",
     *         description="Sorting order ('asc' or 'desc')",
     *         example="desc",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"asc", "desc"}
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of books for the specified travel",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     )
     * )
     *
     * Give a paginated list of the Books.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Book $book, IndexBookRequest $request)
    {
        $books = Book::when($request->priceFrom, function ($query) use ($request) {
            $query->where('price', '>=', $request->priceFrom);
        })
            ->when($request->priceTo, function ($query) use ($request) {
                $query->where('price', '<=', $request->priceTo);
            })
            ->when($request->dateFrom, function ($query) use ($request) {
                $query->where('date_published', '>=', $request->dateFrom);
            })
            ->when($request->dateTo, function ($query) use ($request) {
                $query->where('date_published', '<=', $request->dateTo);
            })
            ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder);
            })
            ->orderBy('date_published')
            ->paginate(config('wefox.perPage'));

        return BookResource::collection($books);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/book/create",
     *     summary="Create a new book",
     *     description="Store a newly created book in the database",
     *     tags={"Api v1 - Books"},
     *     security={{ "sanctum": {} }},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Book details",
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(property="name", type="string", example="new book"),
     *                 @OA\Property(property="sku", type="string", example="cfg-srt"),
     *                 @OA\Property(property="description", type="string", example="content of the first book"),
     *                 @OA\Property(property="date_published", type="date", example="2004-09-01"),
     *                 @OA\Property(property="price", type="float", example="50.10"),
     *                 @OA\Property(property="author", type="string", example="john doe"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Book created successfully",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *                 type="object",
     *
     *                 @OA\Property(property="message", type="string", example="Book created successfully"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     )
     * )
     *
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): BookResource
    {
        $book = Book::create($request->validated());

        return new BookResource($book);
    }

    /**
     *  * @OA\Put(
     *     path="/api/v1/book/update/{book:sku}",
     *     summary="Update a book",
     *     description="Update an existing book in the database base on the give sku",
     *     tags={"Api v1 - Books"},
     *     security={{ "sanctum": {} }},
     *
     *     @OA\Parameter(
     *         name="book:sku",
     *         in="path",
     *         description="sku of the book to update",
     *         required=true,
     *         example="united-arab-emirates",
     *
     *         @OA\Schema(
     *             type="string",
     *             format="varchar",
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Book details",
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(property="name", type="string", example="new united arab emirates"),
     *                 @OA\Property(property="price", type="integer", format="int32", example="5"),
     *                 @OA\Property(property="description", type="string", example="new content"),
     *                 @OA\Property(property="author", type="string", example="John Doe"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Book updated successfully",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *                 type="object",
     *
     *                 @OA\Property(property="message", type="string", example="Book updated successfully"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     )
     * )
     *
     * Updates a an existing Book in storage.
     */
    public function update(Book $book, UpdateBookRequest $request): BookResource
    {
        $book->update($request->validated());

        return new BookResource($book);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/book/delete/{book:sku}",
     *     summary="Delete a book",
     *     description="Delete an existing book in the database base on the give sku",
     *     tags={"Api v1 - Books"},
     *     security={{ "sanctum": {} }},
     *
     *     @OA\Parameter(
     *         name="book:sku",
     *         in="path",
     *         description="sku of the book to delete",
     *         required=true,
     *         example="united-arab-emirates",
     *
     *         @OA\Schema(
     *             type="string",
     *             format="varchar",
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Book deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     )
     * )
     *
     * Remove the specified resource from storage.
     */
    public function delete(Book $book): mixed
    {
        $book->delete();

        return response()->json(null, 204);
    }
}
