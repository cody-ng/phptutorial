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

      $rawProductIds = DB::table('products')
                        ->whereIn('id', $inputProductIds)
                        ->get(['id']);
      $dbProductIds = $rawProductIds->map(function($item, $key){
        return $item->id;
      });

      if( count($inputProductIds) !== count($dbProductIds)){
        $nonExistingProducts = $inputProductIds->diff($dbProductIds);

        return response()->json([
            'Message' => "The following product IDs are not found: {$nonExistingProducts}"
          ],400);
      }

      // 2) input validated at this point.  Save order to database


      return response('success');
      //return response($dbProductIds);
      // return response()->json([
      //   'inputProductCount' => $inputProductCount,
      //   'dbProductCount' => $dbProductCount,
      // ]);



        /* 1) one way to create
       $product = new Product;
       $product->name= $request->name;
       $product->price = $request->price;
       $product->description= $request->description;
       
       $product->save();*/

       // 2) another way to create
       //    (make sure to set the model's "fillable" property)
       //$product = Product::create($request->all());

       //return response()->json($product, 201);
     }


   
}
