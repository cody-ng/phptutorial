<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrdersProductsPivot extends Pivot
{
    protected $table = 'order_products';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;    
    
    protected $hidden = [
        'product_id'
        ,'order_id'
        ,'created_at'
        ,'updated_at'
    ];

}