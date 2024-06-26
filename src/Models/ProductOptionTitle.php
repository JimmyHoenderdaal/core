<?php

namespace Rapidez\Core\Models;

class ProductOptionTitle extends Model
{
    protected $table = 'catalog_product_option_title';

    protected $primaryKey = 'option_title_id';

    public $timestamps = false;

    public function store()
    {
        return $this->belongsTo(config('rapidez.models.store'));
    }

    public function option()
    {
        return $this->belongsTo(config('rapidez.models.product_option'), 'option_id');
    }
}
