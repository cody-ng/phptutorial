<?php

use App\Models\Product;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ProductsTest extends TestCase
{
    //use DatabaseMigrations;
    use DatabaseTransactions;

    private const PRODUCTS_ROUTE = '/api/items';

    /**
     * Create a new product via web API
     *
     * @return void
     */
    public function test_CreateProduct()
    {
        $name = 'ABCEDFGHIJKLMNOPQRSTUVWXYZ';
        $price = 11111;
        $description = 'test description';

        $response = $this->post(self::PRODUCTS_ROUTE, 
                            ['name' => $name,
                             'price' => $price,
                             'description' => $description
                            ]);

    // example result:
    // {
    // "name": "Orange",
    // "price": 11,
    // "description": "fresh grapes",
    // "updated_at": "2021-05-03T21:51:39.000000Z",
    // "created_at": "2021-05-03T21:51:39.000000Z",
    // "id": 59  
    // }
        $this->assertEquals(201, $this->response->status());
        $response
            ->seeJson([
                'name' => $name,
                'price' => $price,
                'description' => $description
            ]);        
        // ->assertJson([
        //     'created' => true,
        // ])
        ;
    }

    /**
     * Update a product via web API
     *
     * @return void
     */
    public function test_UpdateProduct()
    {
        //$name = 'ABCEDFGHIJKLMNOPQRSTUVWXYZ';
        $price = 11111;
        $description = 'test description';

        // create a test product in db
        $product = Product::factory()->create();
        $route = self::PRODUCTS_ROUTE . "/{$product->id}";

        $response = $this->put($route, 
                            ['name' => $product->name,
                             'price' => $price,
                             'description' => $description
                            ]);

//echo $response;
//dd($response);
    // example result:
    // {
    // "name": "Orange",
    // "price": 11,
    // "description": "fresh grapes",
    // "updated_at": "2021-05-03T21:51:39.000000Z",
    // "created_at": "2021-05-03T21:51:39.000000Z",
    // "id": 59  
    // }
        $this->assertEquals(200, $this->response->status());
        $response
            ->seeJson([
                'name' => $product->name,
                'price' => $price,
                'description' => $description
            ]);        
        ;
    }    

    /**
     * Delete a product via web API
     *
     * @return void
     */
    public function test_DeleteProduct()
    {
        //DB::table('Product')->truncate();

        //Product::truncate();

        // create a test product in db
        $product = Product::factory()->create();
        $route = self::PRODUCTS_ROUTE . "/{$product->id}";
        //echo $route;

        $this->delete($route);
        $this->assertEquals(200, $this->response->status());
    }    

}
