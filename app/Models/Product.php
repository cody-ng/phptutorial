<?php

namespace App\Models;

use DateTimeInterface;

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

    protected $hidden = [
        'created_at'
        ,'updated_at'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        // https://www.php.net/manual/en/class.datetimeinterface.php
        return $date->format('m-d-Y H:i:s');
    }


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
