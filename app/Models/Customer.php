<?php

namespace App\Models;

use DateTimeInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    //use HasFactory;

    protected $table = 'customers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name'
    ];

    protected $hidden = [
        'first_name', 'last_name', 'updated_at'
    ];

    protected $appends = ['full_name'];

    protected function serializeDate(DateTimeInterface $date)
    {
        // https://www.php.net/manual/en/class.datetimeinterface.php
        return $date->format('m-d-Y h:i:s a');
    }

    public function getFullNameAttribute()
    {
        //return $this->first_name . ' ' . $this->last_name;
        return "{$this->first_name} {$this->last_name}";
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
