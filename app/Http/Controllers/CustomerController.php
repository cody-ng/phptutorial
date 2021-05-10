<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
     public function index(Request $request)
     {
       $customers = Customer::all();

       //$filtered = $customers->only(['id', 'name']);       
       $filtered = $customers->map(function ($item, $key) {
         return [
            'id' => $item->id,
            'name' => $item->name()
            //'creation_date' => $item->created_at->format('m/d/Y')
        ];
     
       });
       return response()->json($filtered);
     }


   
}
