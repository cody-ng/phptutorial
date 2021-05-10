<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;

use DB;

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
       $orders = Order::all();

       //$filtered = $customers->only(['id', 'name']);       
      //  $filtered = $customers->map(function ($item, $key) {
      //    return [
      //       'id' => $item->id,
      //       'name' => $item->name()
      //       //'creation_date' => $item->created_at->format('m/d/Y')
      //   ];
     
      //  });
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

        $rawResult = DB::table('customers')
                          ->where('id', '=', $inputCustomerId)
                          ->first(['id']);
        $isFound = false;

        if( $rawResult !== null ){
          $dbCustomerId = $rawResult->id;
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

      // 3) return the new order info
      return response($order, 201);
     }


   
}
