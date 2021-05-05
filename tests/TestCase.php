<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function AssertEqualsWithError($code)
    {
        $errMessage = $this->response->getContent();
        $this->assertEquals($code, $this->response->status(), $errMessage);
    }

}
