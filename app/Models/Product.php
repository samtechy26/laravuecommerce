<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        "title","slug","description", "sku", "published","inStock","price", "weight", "created_by","updated_by","deleted_by"
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function product_images() {
        return $this->hasMany(Product_Image::class);
    }

   public function sizes() {
    return $this->belongsToMany(Size::class, 'product_size')->using(ProductSize::class);
   }

   public function colors() {
    return $this->belongsToMany(Color::class, 'product_color')->using(ProductColor::class);
   }

   public function materials() {
    return $this->belongsToMany(Material::class)->using(ProductMaterial::class);
   }

    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function cartItems() {
        return $this->hasMany(CartItems::class);
    }

    public function orderItems () {
        return $this->hasMany(OrderItem::class);
    }

    // filter logic for price, category and brand

    public function scopeFiltered(Builder $builder) {
        $builder->when(request('brands'), function (Builder $q) {
            $q->whereIn('brand_id', request(('brands')));
        })->when(request('categories'), function (Builder $q) {
            $q->whereIn('category_id', request('categories'));
        })->when(request('prices'), function (Builder $q) {
            $q->whereBetween('price', [
                request('prices.from', 0),
                request('prices.to', 1000000)
            ]);
        })->when(request('color'), function (Builder $q) {
            $q->whereHas('colors', function (Builder $q) {
                $q->where('colors.id', request('color'));
            });
        })->when(request('size'), function (Builder $q) {
            $q->whereHas('sizes', function (Builder $q) {
                $q->where('sizes.id', request('size'));
            });
        });


        // Sorting by price
        $builder->when(request('sort_by') === 'price_asc', function (Builder $q) {
            $q->orderBy('price', 'asc');
        })->when(request('sort_by') === 'price_desc', function (Builder $q) {
            $q->orderBy('price', 'desc');
        });

        // Sorting by date created
        $builder->when(request('sort_by') === 'date_asc', function (Builder $q) {
            $q->orderBy('created_at', 'asc');
        })->when(request('sort_by') === 'date_desc', function (Builder $q) {
            $q->orderBy('created_at', 'desc');
        });
    }
}
