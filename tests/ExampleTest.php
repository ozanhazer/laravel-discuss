<?php

namespace Alfatron\Discussions\Tests;

use Orchestra\Testbench\TestCase;
use Alfatron\Discussions\DiscussionsServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [DiscussionsServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
