<?php

namespace App\Nova;

use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Book extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Book>
     */
    public static $model = \App\Models\Book::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array<int,string>
     */
    public static $search = [
        'name', '',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int,mixed>
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable(),

            Text::make('sku')
                ->sortable(),

            Textarea::make('Description')
                ->hideFromIndex(),

            Text::make('Author')
                ->sortable(),

            Date::make('Date Published')
                ->sortable(),

            Currency::make('Price')
                ->currency('EUR')
                ->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array<int,mixed>
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int,mixed>
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int,mixed>
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int,mixed>
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
