<?php
namespace BenBjurstrom\EloquentPostgresUuids\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use BenBjurstrom\EloquentPostgresUuids\Tests\Fixtures\User;

abstract class TestCase extends Orchestra
{
    use DatabaseTransactions;
    public function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(realpath(__DIR__.'/Fixtures'));
        $this->artisan('migrate');

        \Route::get('users/{user}', function (User $user) {
            return $user->user_id;
        })->middleware(SubstituteBindings::class);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', true);
        $app['config']->set('database.default', 'pgsql');
        $app['config']->set('database.connections.pgsql', [
            'driver'    => 'pgsql',
            'host'      => env('DB_HOST', '127.0.0.1'),
            'port'      => env('DB_PORT', '5432'),
            'database'  => env('DB_DATABASE', 'postgres'),
            'username'  => env('DB_USERNAME', 'postgres'),
            'password'  => env('DB_PASSWORD', 'postgres'),
            'charset'   => 'utf8',
            'prefix'    => '',
            'schema'    => 'public',
            'sslmode'   => 'prefer',
        ]);
    }
}
