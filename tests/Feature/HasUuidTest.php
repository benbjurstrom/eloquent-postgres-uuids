<?php

namespace BenBjurstrom\EloquentPostgresUuids\Tests;

use BenBjurstrom\EloquentPostgresUuids\Tests\Fixtures\User;

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

        $this->assertEquals(36, strlen($user->id));
    }

    /**
     * @test
     */
    public function testFindRecord()
    {
        $user  = new User();
        $user->save();
        $user->refresh();

        $result = User::findOrFail($user->id);
        $this->assertEquals($user->id, $result->id);
    }
}