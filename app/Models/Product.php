<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{

    protected $table = 'products';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price','description'
    ];

    // public function orderDetails()
    // {
    //     return $this->hasMany(OrderProduct::class);
    // }

    public function orders()
    {
        //return $this->belongsToMany(Order::class);
        return $this->belongsToMany(Order::class, 
                                    'orders_products',
                                    'product_id', // this table's FK in relationship table
                                    'order_id'
                                )
                    //->withPivot('quantity', 'price')
                    ->withTimestamps()
                    ;
    }

}
