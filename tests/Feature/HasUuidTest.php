<?php

namespace BenBjurstrom\EloquentPostgresUuids\Tests;

use BenBjurstrom\EloquentPostgresUuids\Tests\Fixtures\User;
use BenBjurstrom\EloquentPostgresUuids\ValidateUuid;

class HasUuidTest extends TestCase
{
    /**
     * @test
     */
    public function testCreateRecord()
    {
        $user  = new User();
        $user->save();
        $user->refresh();

        $validator = app('validator')->make(
            ['id' => $user->user_id],
            ['id' => [new ValidateUuid]]
        );

        $this->assertTrue($validator->passes());
    }

    /**
     * @test
     */
    public function testFindRecord()
    {
        $user  = new User();
        $user->save();
        $user->refresh();

        $result = User::findOrFail($user->user_id);
        $this->assertEquals($user->user_id, $result->user_id);
    }

    public function testRouteModelBinding()
    {
        $user  = new User();
        $user->save();
        $user->refresh();

        $this->getJson(url('/users/' . $user->user_id))
            ->assertStatus(200)
            ->assertSeeText($user->user_id);
    }

    public function testRouteModelBindingFailsGracefully()
    {
        $user  = new User();
        $user->save();
        $user->refresh();

        $response = $this->getJson(url('/users/' . 1))
            ->assertStatus(422);
    }
}