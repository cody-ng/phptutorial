<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        //return response($request->headers);
     
       $products = Product::all();
       return response()->json($products);
     }

     public function create(Request $request)
     {
        $this->validate($request, [
           //'name' => 'required|alpha',
           'name' => 'required|regex:/^[\pL\s\-]+$/u',
           'price' => 'required|integer',
           'description' => 'string|nullable'
        ]);

        /* 1) one way to create
       $product = new Product;
       $product->name= $request->name;
       $product->price = $request->price;
       $product->description= $request->description;
       
       $product->save();*/

       // 2) another way to create
       //    (make sure to set the model's "fillable" property)
       $product = Product::create($request->all());

       return response()->json($product, 201);
     }

     public function show($id)
     {
        $product = Product::find($id);
        return response()->json($product);
     }

     public function update(Request $request, $id)
     { 
        $this->validate($request, [
           //'name' => 'required|alpha',
           'name' => 'required|regex:/^[\pL\s\-]+$/u',
           'price' => 'required|integer',
           'description' => 'string|nullable'
        ]);
        /* // 1) one way to update
        $product= Product::find($id);
        
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->save();
        */

        // 2) another way to update
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json($product, 200);
     }

     public function destroy($id)
     {
        //$product = Product::find($id);
        $product = Product::findOrFail($id);
        
        $product->delete();
        return response()->json('product removed successfully', 200);
     }
   
}
