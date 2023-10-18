<?php

namespace App\Models;

use App\Models\Product;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model implements HasMedia
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
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        "id" => "string"
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
        "media"
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
        "imageUrl"
    ];

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
     * The categories that belong to the product.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): BelongsToMany
    {
        return $this->BelongsToMany(Product::class)->orderBy("order");
    }

    /**
     * Register media conversions for the resource.
     *
     * @param  \Spatie\MediaLibrary\MediaCollections\Models\Media $media
     * @return void
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('medium-size')
            ->width(295)
            ->height(295)
            ->keepOriginalImageFormat();
    }

    /**
     * Register media collection for the resource.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('category_images')->singleFile();
    }
}
