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
     * Create a new product via web API, with invalid input name
     *
     * @return void
     */
    public function test_CreateProduct_InvalidName()
    {
        $response = $this->call('POST', self::PRODUCTS_ROUTE, 
                            ['name' => 'Orange1', // letters only
                             'price' => 111,
                             'description' => 'Valid description'
                            ]);
        // expected result:
        // {
        //     "name": [
        //         "The name format is invalid."
        //     ]
        // }                            

        $this->assertEquals(422, $this->response->status());
        $result = $response->json()['name'];
        $errorString = 'The name format is invalid.';
        $this->assertContains($errorString, $result, "Expected error string is not found.");
    }

    /**
     * Create a new product via web API, with invalid input price
     *
     * @return void
     */
    public function test_CreateProduct_InvalidPrice()
    {
        $response = $this->call('POST', self::PRODUCTS_ROUTE, 
                            ['name' => 'Orange', 
                             'price' => 'abc', // must be integer
                             'description' => 'Valid description'
                            ]);
        // expected result:
        // {
        //     "price": [
        //         "The price must be an integer."
        //     ]
        // }
        $this->assertEquals(422, $this->response->status());
        $result = $response->json()['price'];
        $errorString = 'The price must be an integer.';
        $this->assertContains($errorString, $result, "Expected error string is not found.");
    }

    /**
     * Create a new product via web API, with invalid input description
     *
     * @return void
     */
    public function test_CreateProduct_InvalidDescription()
    {
        $response = $this->call('POST', self::PRODUCTS_ROUTE, 
                            ['name' => 'Orange', 
                             'price' => 111,
                             'description' => 222
                            ]);
        // expected result:
        // {
        //     "description": [
        //         "The description must be a string."
        //     ]
        // }
        $this->assertEquals(422, $this->response->status());
        $result = $response->json()['description'];
        $errorString = 'The description must be a string.';
        $this->assertContains($errorString, $result, "Expected error string is not found.");

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
    }    

    /**
     * Update a product via web API, with invalid input name
     *
     * @return void
     */
    public function test_UpdateProduct_InvalidName()
    {
       $route = self::PRODUCTS_ROUTE . "/1"; // id doesn't matter

        $response = $this->call('PUT', $route, 
                            ['name' => 'Orange1', // letters only
                             'price' => 111,
                             'description' => 'Valid description'
                            ]);
        // expected result:
        // {
        //     "name": [
        //         "The name format is invalid."
        //     ]
        // }                            
        $this->assertEquals(422, $this->response->status());
        $result = $response->json()['name'];
        $errorString = 'The name format is invalid.';
        $this->assertContains($errorString, $result, "Expected error string is not found.");
    }        

    /**
     * Update a product via web API, with invalid input price
     *
     * @return void
     */
    public function test_UpdateProduct_InvalidPrice()
    {
       $route = self::PRODUCTS_ROUTE . "/1"; // id doesn't matter

        $response = $this->call('PUT', $route, 
                            ['name' => 'Orange',
                             'price' => 'abc', // must be integer
                             'description' => 'Valid description'
                            ]);
        // expected result:
        // {
        //     "price": [
        //         "The price must be an integer."
        //     ]
        // }
        $this->assertEquals(422, $this->response->status());
        $result = $response->json()['price'];
        $errorString = 'The price must be an integer.';
        $this->assertContains($errorString, $result, "Expected error string is not found.");
    }        

    /**
     * Update a product via web API, with invalid input description
     *
     * @return void
     */
    public function test_UpdateProduct_InvalidDescription()
    {
       $route = self::PRODUCTS_ROUTE . "/1"; // id doesn't matter

        $response = $this->call('PUT', $route, 
                            ['name' => 'Orange',
                             'price' => 111,
                             'description' => 222 // must be alpha numeric
                            ]);
        // expected result:
        // {
        //     "description": [
        //         "The description must be a string."
        //     ]
        // }
        $this->assertEquals(422, $this->response->status());
        $result = $response->json()['description'];
        $errorString = 'The description must be a string.';
        $this->assertContains($errorString, $result, "Expected error string is not found.");
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

    /**
     * Get a product via web API
     *
     * @return void
     */
    public function test_GetSingleProduct()
    {
        // create a test product in db
        $product = Product::factory()->create();
        $route = self::PRODUCTS_ROUTE . "/{$product->id}";

        $response = $this->get($route);

        $this->assertEquals(200, $this->response->status());
        $response
            ->seeJson([
                'name' => $product->name,
                'price' => $product->price,
                'description' => $product->description,
                'id' => $product->id,
            ]);        
        ;
    }    

    /**
     * Get all products via web API
     *
     * @return void
     */
    public function test_GetAllProducts()
    {
        // get existing count, in case the product's table is not empty
        $products = $this->call('GET', self::PRODUCTS_ROUTE)->json();
        $existingProductCount = count($products);

        $productCount = 5;
        // create products in db for testing
        Product::factory()
                ->count($productCount)
                ->create();

        $response = $this->call('GET', self::PRODUCTS_ROUTE);

        // a list of these items as result:
        // {
        // "name": "Orange",
        // "price": 11,
        // "description": "fresh grapes",
        // "updated_at": "2021-05-03T21:51:39.000000Z",
        // "created_at": "2021-05-03T21:51:39.000000Z",
        // "id": 59  
        // }

        $this->assertEquals(200, $this->response->status());

        $products = $response->json();
        //dd($products);
        $this->assertEquals($productCount + $existingProductCount, count($products));

    }    

}
