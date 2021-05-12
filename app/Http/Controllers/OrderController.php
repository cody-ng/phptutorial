<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;

use DB;

use Illuminate\Http\Request;


class OrderController extends Controller
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
      //$orders = Order::all();
      $orders = Order::with('customerObj')->get();
      //dd($orders[0]->customerObj->full_name);
    
      return response()->json($orders);
    }

    public function create(Request $request)
    {
      //$data = (object)$request->json()->all();
      //dd($data);

      // 1a) validate input
      $this->validate($request, [
          'customer_id' => 'required|numeric',
          "products"    => "required|array|min:1",
          'products.*.product_id' => 'required|numeric',
          'products.*.quantity' => 'required|numeric'
      ]);

      // 1b) validate the customer IDs are valid
      $inputCustomerId = $request['customer_id'];
      $inputProducts = $request['products'];
      //dd($request);

      // $rawCustomer = DB::table('customers') // returns json, but not the model class
      $rawCustomer = Customer::
                        where('id', '=', $inputCustomerId)
                        ->first();
      $isFound = false;

      if( $rawCustomer !== null ){
        $dbCustomerId = $rawCustomer->id;
        $isFound = ($inputCustomerId === $dbCustomerId);
      }

      if( !$isFound ){
        return response()->json([
          'Message' => "The customer ID is not found: ({$inputCustomerId})."
        ],400);
    }

    // 1c) validate the product IDs are valid
    $inputProductIds = collect(array_column($inputProducts, 'product_id'));

    $rawProducts = DB::table('products')
                      ->whereIn('id', $inputProductIds)
                      ->get(['id', 'price']);
    $dbProductIds = $rawProducts->map(function($item, $key){
      return $item->id;
    });

    if( count($inputProductIds) !== count($dbProductIds)){
      $nonExistingProducts = $inputProductIds->diff($dbProductIds);

      return response()->json([
          'Message' => "The following product IDs are not found: {$nonExistingProducts}"
        ],400);
    }

    // 2) input validated at this point.  Save order to database
    // 2a) calculate order total
    $total = 0;

    // one way to loop thru array
    foreach ($inputProducts as $key => $p) {
      $temp = $rawProducts->firstWhere('id', '=', $p['product_id']);
      $p['price'] = $temp->price; // add price field to the array sync()
      $inputProducts[$key] = $p;
      $total += $p['price'] * $p['quantity'];
    } 
    unset($p); // good practice to delete the loop variable
    unset($temp); // need this?

    // second way requires a collection to use map()
    // $inputProducts = $inputProducts->map(function($p) { 
    //   $temp = $rawProducts->firstWhere('id', '=', $p['product_id']);
    //   $p['price'] = $temp->price;
    //   return $p; 
    // });      

    // 2b) create order
    $order = Order::create([
      'customer_id' => $inputCustomerId,
      'total' => $total,
    ]);

    // 2c) create the list of products using relationship
    $order->products()->sync($inputProducts);

    // 2d) load the customer name for this order
    //$order->load('customerObj'); // load from db, or
    $order->customer_name = $rawCustomer->full_name;

    // 3) return the new order info
    return response($order, 201);
    }

    public function orderDetails($id)
    {
      $order = Order::with(['customerObj', 'products'])
                      ->where('id', '=', $id)
                      ->first();
      if( is_null($order) )
      {
        return response()->json([
          'Message' => "The product ID is not found: {$id}"
        ], 400);
      }

      
      return response($order, 200);
    }


   
}
