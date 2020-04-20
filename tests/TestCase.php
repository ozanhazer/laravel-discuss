<?php

namespace Alfatron\Discuss\Tests;

use Alfatron\Discuss\DiscussServiceProvider;
use Alfatron\Discuss\Tests\HelperClasses\User;
use Illuminate\Support\Facades\Redis;
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
        $app['config']->set('database.redis.options.prefix', 'testbench_');
        $app['config']->set('database.redis.default.database', 5);

        // Use our dummy class for the tests
        $app['config']->set('discuss.user_model', User::class);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testbench'])->run();
        $this->loadLaravelMigrations();

        // We have a test for invalid redis port, this will cause
        // the test fail before even starting without the if stmt.
        if (config('database.redis.default.port') == 6379) {
            Redis::flushAll();
        }
    }
}
