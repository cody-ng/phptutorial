<?php

namespace App\Models;

use DateTimeInterface;

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

    protected $hidden = [
        'created_at'
        ,'customerObj'
    ];

    // shows up in json serialization
    protected $appends = [
        'customer_name'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        // https://www.php.net/manual/en/class.datetimeinterface.php
        return $date->format('m-d-Y h:i:s a');
    }

    // add a new custom field to the model
    public function getCustomerNameAttribute()
    {
        // if relationship object is loaded, return from there
        // https://echebaby.com/blog/2021-01-05-laravel-check-if-a-relation-is-loaded-on-Eloquent-model/
        if( $this->relationLoaded('customerObj') )
        {
            return $this->customerObj->full_name;
        }
        // otherwise, if manually set, return it
        else if (isset($this->attributes['customer_name'] ))
        {
            return $this->attributes['customer_name'];
        }

        // never been set
        return 'n/a';
    }

    public function setCustomerNameAttribute($value)
    {
        $this->attributes['customer_name'] = $value;
    }    

    public function customerObj()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function products()
    {
        //return $this->belongsToMany(Product::class);
        return $this->belongsToMany(Product::class, 
                                    'orders_products',
                                    'order_id', // this table's FK in relationship table
                                    'product_id' 
                                )
                    ->withPivot('quantity', 'price')
                    ->using(OrdersProductsPivot::class) // custom pivot class
                    ->as('orderDetails')                    
                    ->withTimestamps()
                    ;

    }


}
