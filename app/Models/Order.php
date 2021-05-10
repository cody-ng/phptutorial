<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{

    protected $table = 'orders';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total', 'customer_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // public function orderDetails()
    // {
    //     return $this->hasMany(OrderProduct::class);
    // }

    public function products()
    {
        //return $this->belongsToMany(Product::class);
        return $this->belongsToMany(Product::class, 
                                    'orders_products',
                                    'order_id', // this table's FK in relationship table
                                    'product_id' 
                                )
                    ->withPivot('quantity', 'price')
                    ->as('orderDetails')                    
                    ->withTimestamps()
                    ;

    }


}
