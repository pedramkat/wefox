<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * The table name of this model.
     *
     * @var string
     */
    protected $table = 'books';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'sku',
        'name',
        'description',
        'date_published',
        'price',
        'author',
    ];

    /**
     * Casting attributes.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_published' => 'date:Y-m-d',
        'price' => 'integer',
    ];
}
