<?php

use App\Models\Product;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ProductsAuth0Test extends TestCase
{
    //use DatabaseMigrations;
    use DatabaseTransactions;

    private const PRODUCTS_AUTH_ROUTE = '/auth/api/items';
    private const PRODUCTS_ROUTE = '/api/items';

    /**
     * Set up the test
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->token = env('AUTH0_TOKEN', null);
        $this->assertNotNull($this->token, 'Missing Auth0 token');
    }    

    protected function getHeaders()
    {
        $headers = array(
            'HTTP_Authorization' => "Bearer {$this->token}"
         );
        //  $headers = [
        //     'HTTP_Authorization' => "Bearer {$this->token}"
        //     //'Authorization' => "Bearer " . $this->token
        //     //'content_type' => 'application-json'
        //  ];
         //dd($headers);
         return $headers;

        // Examples of passing the header:
        // 1)
        //$response = $this->call('POST', '/token/invalidate',
        //[/* params */], [/* cookies */], [/* files */], ['HTTP_Authorization' => 'Bearer '.$user->token]);         
        //
        // 2)
        // $request = $this->get('auth/logout', [
        //     'HTTP_Authorization' => 'Bearer ' . $token
        // ]);
        //
        // 3)
        // $this->json('POST', 'users/3', ['name' => 'Sally'], ['HTTP_Authorization' => $this->token]);
    }

    /**
     * Create a new product via web API
     *
     * @return void
     */
    public function testAuth_CreateProduct()
    {
        $name = 'ABCEDFGHIJKLMNOPQRSTUVWXYZ';
        $price = 11111;
        $description = 'test description';

        $response = $this->post(self::PRODUCTS_AUTH_ROUTE, 
                            ['name' => $name,
                             'price' => $price,
                             'description' => $description
                            ],
                            $this->getHeaders()
                        );
        // example result:
        // {
        // "name": "Orange",
        // "price": 11,
        // "description": "fresh grapes",
        // "updated_at": "2021-05-03T21:51:39.000000Z",
        // "created_at": "2021-05-03T21:51:39.000000Z",
        // "id": 59  
        // }
        $this->AssertEqualsWithError(201);

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
     * Create a new product via web API without auth0 token
     *
     * @return void
     */
    public function testAuth_CreateProduct_NoAuthToken()
    {
        $name = 'ABCEDFGHIJKLMNOPQRSTUVWXYZ';
        $price = 11111;
        $description = 'test description';

        $response = $this->post(self::PRODUCTS_AUTH_ROUTE, 
                            ['name' => $name,
                             'price' => $price,
                             'description' => $description
                            ]
                        );
        $this->AssertEqualsWithError(401);
    }

    /**
     * Update a product via web API
     *
     * @return void
     */
    public function testAuth_UpdateProduct()
    {
        $name = 'new test product name';
        $price = 11111;
        $description = 'test description';

        // create a test product in db
        $product = Product::factory()->create();
        $route = self::PRODUCTS_AUTH_ROUTE . "/{$product->id}";

        $response = $this->put($route, 
                            ['name' => $name,
                             'price' => $price,
                             'description' => $description
                            ],
                            $this->getHeaders()
                        );

        // example result:
        // {
        // "name": "Orange",
        // "price": 11,
        // "description": "fresh grapes",
        // "updated_at": "2021-05-03T21:51:39.000000Z",
        // "created_at": "2021-05-03T21:51:39.000000Z",
        // "id": 59  
        // }
        $this->AssertEqualsWithError(200);

        $response
            ->seeJson([
                'name' => $name,
                'price' => $price,
                'description' => $description
            ]);        
    }    

    /**
     * Update a product via web API w/o Auth0 token
     *
     * @return void
     */
    public function testAuth_UpdateProduct_NoAuthToken()
    {
        $price = 11111;
        $description = 'test description';

        // create a test product in db
        $product = Product::factory()->create();
        $route = self::PRODUCTS_AUTH_ROUTE . "/{$product->id}";

        $response = $this->put($route, 
                            ['name' => $product->name,
                             'price' => $price,
                             'description' => $description
                            ]
                        );
        $this->AssertEqualsWithError(401);
    }    

    /**
     * Delete a product via web API
     *
     * @return void
     */
    public function testAuth_DeleteProduct()
    {
        //DB::table('Product')->truncate();
        //Product::truncate();

        // create a test product in db
        $product = Product::factory()->create();
        $route = self::PRODUCTS_AUTH_ROUTE . "/{$product->id}";

        // $this->post($url, $parameters, $header);
        // $this->put($url, $parameters, $header);
        // $this->delete($url, $parameters, $header);        
        $this->delete($route, [], $this->getHeaders());
        
        $this->AssertEqualsWithError(200);
    }    

    /**
     * Delete a product via web API w/o Auth0 token
     *
     * @return void
     */
    public function testAuth_DeleteProduct_NoAuthToken()
    {
        //DB::table('Product')->truncate();
        //Product::truncate();

        // create a test product in db
        $product = Product::factory()->create();
        $route = self::PRODUCTS_AUTH_ROUTE . "/{$product->id}";

        // $this->post($url, $parameters, $header);
        // $this->put($url, $parameters, $header);
        // $this->delete($url, $parameters, $header);        
        $response = $this->delete($route);

        $this->AssertEqualsWithError(401);

    }    

    /**
     * Get a product via web API
     *
     * @return void
     */
    public function testAuth_GetSingleProduct()
    {
        // create a test product in db
        $product = Product::factory()->create();
        $route = self::PRODUCTS_AUTH_ROUTE . "/{$product->id}";

        // $response = $this->call('GET', $route,
        //                         [], [], [],
        //                         $this->getHeaders()
        //                         );
        // seeJson() doesn't work with call()

        $response = $this->get($route,
                            $this->getHeaders()
                            );

        $this->AssertEqualsWithError(200);
                    
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
     * Get a product via web API w/o Auth0 token
     *
     * @return void
     */
    public function testAuth_GetSingleProduct_NoAuthToken()
    {
        // create a test product in db
        $product = Product::factory()->create();
        $route = self::PRODUCTS_AUTH_ROUTE . "/{$product->id}";

        $response = $this->get($route);

        $this->AssertEqualsWithError(401);
    }    

    /**
     * Get all products via web API
     *
     * @return void
     */
    public function testAuth_GetAllProducts()
    {
        $this->refreshApplication();
        // get existing count, in case the product's table is not empty
        $response = $this->call('GET', self::PRODUCTS_AUTH_ROUTE,
                                [], [], [],
                                //['HTTP_Authorization' => "Bearer " . $this->token]
                                $this->getHeaders()
                                );
        $this->AssertEqualsWithError(200);

        $products = $response->json();
        $existingProductCount = count($products);

        $productCount = 5;
        // create products in db for testing
        Product::factory()
                ->count($productCount)
                ->create();

        $response = $this->call('GET', self::PRODUCTS_AUTH_ROUTE,
                                [], [], [],
                                $this->getHeaders()
                            );

        $this->AssertEqualsWithError(200);
                    
        $products = $response->json();
        
        $this->assertEquals($productCount + $existingProductCount, count($products));
    }    

    /**
     * Get all products via web API w/o token
     *
     * @return void
     */
    public function testAuth_GetAllProducts_NoAuthToken()
    {
        $this->refreshApplication();
        // get existing count, in case the product's table is not empty
        $response = $this->call('GET', self::PRODUCTS_AUTH_ROUTE);

        $this->AssertEqualsWithError(401);
    }        
}
