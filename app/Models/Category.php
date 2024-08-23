<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'eId',
    ];

    /**
     * Use timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Many-to-many relationship
     *
     * @return belongsToMany
     */
    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_eId', 'product_eId', 'eId');
    }
}
