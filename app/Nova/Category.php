<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Avatar;
use Benjaminhirsch\NovaSlugField\Slug;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use Benjaminhirsch\NovaSlugField\TextWithSlug;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class Category extends Resource
{
    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Category::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name_ru';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name_ru',
    ];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Меню';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Категории');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Категория');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            // ID::make(__('ID'), 'id')->sortable(),
            Avatar::make('Фото', function () {
                $image = $this->media[0]->getUrl();
                $path = env('APP_URL') . '/storage/';
                return str_replace($path, '',  $image);
            })
                ->disableDownload()
                ->maxWidth(40)
                ->onlyOnIndex(),
            TextWithSlug::make('Название RU', 'name_ru')
                ->required()
                ->placeholder('Название продукта на русском')
                ->slug('slug'),
            Text::make('Название KK', 'name_kk')
                ->placeholder('Название продукта на казахском')
                ->required()
                ->hideFromIndex(),
            Text::make('Название EN', 'name_en')
                ->placeholder('Название продукта на английском')
                ->required()
                ->hideFromIndex(),
            Images::make('Фото', 'category_images')
                ->showStatistics()
                ->singleMediaRules('max:2024')
                ->required()
                ->setFileName(function ($originalFilename, $extension, $model) {
                    $count = $model->media->count() == 0 ? 1 : $model->media->count();
                    return $model->slug . '-' . $model->id . '-' . $count . '.' . $extension;
                })->hideFromIndex(),
            BelongsToMany::make('Продукты', 'products', 'App\Nova\Product')
                ->searchable(),
            Slug::make('Транскрипция', 'slug')
                ->withMeta([
                    'extraAttributes' => [
                        'readonly' => true,
                    ]
                ])
                ->hideFromIndex()
                ->required(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
