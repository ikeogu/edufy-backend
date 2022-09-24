<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SchoolTest extends TestCase
{
    use RefreshDatabase;

    private function data(){

        Storage::fake('avatars');

        return [
            'name' => 'School 1',
            'location' => 'Location 1',
            'email' => 'school_@mail.com',
            'phone' => '08012345678',
            'website' => 'www.school.com',
            // 'logo' => UploadedFile::fake()->image('avatar.jpg'),
            'description' => 'This is a school',
            'status' => 1,
            'school_category'=> [
                [
                    'name' => 'Nursery School',
                    'description' => 'Just usery 1 -3'
                ],
                [
                    'name' => 'Primary School',
                    'description' => 'Just usery 1 -3'
                ],
                [
                    'name' => 'Junior Secondary School',
                    'description' => 'Just usery 1 -3'
                ],
                 [
                    'name' => 'Senior Secondary School',
                    'description' => 'Just usery 1 -3'
                ]

            ]
        ];
    }

    /** @test  */
    public function setup_school(){

        $this->withoutExceptionHandling();
        $user= User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response = $this->postJson(route('set_up_school'), $this->data());

        $response->assertStatus(200);

    }
}
