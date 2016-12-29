<?php

namespace Tests;

use Naweown\User;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function getRouteFor(string $routeName)
    {
        return route($routeName);
    }
    
    protected function modelFactoryFor(string $class, array $values = [], string $action = 'create')
    {
        return factory($class)->$action($values);
    }
}
