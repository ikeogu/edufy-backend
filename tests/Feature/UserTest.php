<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
  use RefreshDatabase;

  /** @test */

    public function user_can_be_created()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'johnydoe@gmail.com',
            'password' => 'password',
            'role' => 'school_admin',
            'phone' => '1234567890',
            'avatar' => 'avatar.jpg'
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, \App\Models\User::all());
    }

    /** @test */

    public function user_can_be_logged_in()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'johnydoe@gmail.com',
            'password' => 'password',
            'role' => 'school_admin',
            'phone' => '1234567890',
            'avatar' => 'avatar.jpg'
        ]);

        $response = $this->post('/api/login', [
            'email' => 'johnydoe@gmail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200);
    }

    /** @test */

    public function user_can_be_logged_out()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' =>'johnydoe@gmail.com',
            'password' => 'password',
            'role' => 'school_admin',
            'phone' => '1234567890',
            'avatar' => 'avatar.jpg'
        ]);

        $response = $this->post('/api/login', [
            'email' => 'johnydoe@gmail.com',
            'password' => 'password'
        ]);

        $response = $this->post('/api/logout', [
            'email' =>'johnydoe@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    /** @test */

    public function user_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create()->first();
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'johnydoe@gmail.com',
            'password' => 'password',
            'role' => 'school_admin',
            'phone' => '1234567890',
            'avatar' => 'avatar.jpg'
        ]);

        $response = $this->post('/api/login', [
            'email' => 'johnydoe@gmail.com',
            'password' => 'password'
        ]);

        $response = $this->delete('api/management/user/delete/' . $user->id, $this->data());

        $this->assertCount(1, \App\Models\User::all());
    }

    /** @test */

    public function user_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' =>'johnydoe@gmail.com',
            'password' => 'password',
            'role' => 'school_admin',
            'phone' => '1234567890',
            'avatar' => 'avatar.jpg'
        ]);

        $response = $this->post('/api/login', [
            'email' => 'johnydoe@gmail.com',
            'password' => 'password'
        ]);

        $response = $this->put('/api/management/user/1', $this->data());

        $response->assertStatus(200);

        $this->assertCount(1, \App\Models\User::all());

    }


    private function data()
    {
        return [
            'name' => 'John Doye',
            'email' => 'johnydo3@gmail.com',
            'password' => 'password',
            'role' => 'school_admin',
            'phone' => '1234567890',
            'avatar' => 'avatar.jpg'
        ];
    }

}
