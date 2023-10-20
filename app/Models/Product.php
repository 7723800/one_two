<?php

namespace App\Models;

use App\Models\Category;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SortableTrait;

    /**
     * The name of the column that will be used to sort models.
     *
     * Define if the models should sort when creating. When true, the package
     * will automatically assign the highest order number to a new model
     */
    public $sortable = [
        "order_column_name" => "order",
        "sort_when_creating" => true,
    ];

    /**
     * The attributes that displays the presence.
     *
     * @var bool
     */
    protected $isOutOfStock = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        "id" => "string"
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        "media"
    ];

    /**
     * The accessors to append to the model"s array form.
     *
     * @var array
     */
    protected $appends = [
        "imageUrl",
        "description",
        "isOutOfStock",
        "name"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at",
        "order",
        "name_ru",
        "name_kk",
        "name_en",
        "description_ru",
        "description_kk",
        "description_en",
        "active",
        "pivot",
        "media"
    ];

    /**
     * Localized product name.
     *
     * @return string
     */
    public function getNameAttribute(): ?string
    {
        $column = "name_" . config("app.locale");
        return $this->getAttribute($column);
    }

    /**
     * Product image url.
     *
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        return count($this->media) > 0 ? $this->media[0]->getUrl() : "";
    }

    /**
     * Model price attribute.
     *
     * @return bool
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->isOutOfStock;
    }

    /**
     * Localized product description.
     *
     * @return ?string
     */
    public function getDescriptionAttribute(): ?string
    {
        $column = "description_" . config("app.locale");
        return $this->getAttribute($column);
    }

    /**
     * The products that belong to the category.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Register media conversions for the resource.
     *
     * @param  \Spatie\MediaLibrary\MediaCollections\Models\Media $media
     * @return void
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(292)
            ->height(292)
            ->keepOriginalImageFormat();

        $this->addMediaConversion('medium-size')
            ->width(1045)
            ->height(1045)
            ->keepOriginalImageFormat();
    }

    /**
     * Register media collection for the resource.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images')->singleFile();
    }
}
