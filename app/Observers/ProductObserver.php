<?php

namespace App\Observers;

use App\Events\ProductCreated;
use App\Events\ProductUpdated;
use App\Models\Product;
use Illuminate\Support\Facades\Event;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param Product $product
     * @return void
     */
    public function created(Product $product): void
    {
        Event::dispatch(new ProductCreated($product));
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        Event::dispatch(new ProductUpdated($product));
    }
}
