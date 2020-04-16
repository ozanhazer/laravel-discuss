<?php

namespace Alfatron\Discuss\Tests;

use Alfatron\Discuss\DiscussServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [DiscussServiceProvider::class];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('logging.default', 'daily');
        $app['config']->set('logging.channels.daily.path', __DIR__ . '/../storage/logs/laravel.log');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('app.debug', env('APP_DEBUG', true));

        // Use our dummy class for the tests
        $app['config']->set('discuss.user_model', \User::class);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testbench'])->run();
        $this->loadLaravelMigrations();
    }
}
