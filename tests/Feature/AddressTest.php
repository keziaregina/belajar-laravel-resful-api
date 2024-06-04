<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use Database\Seeders\UserSeeder;
use Database\Seeders\ContactSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends TestCase
{
    function testCreateSuccess() {
        $this->seed([UserSeeder::class,ContactSeeder::class]);
        $contact = Contact::first();

        $this->post("/api/contacts/{$contact->id}/addresses",[
            'street' => 'test',
            'city' => 'test',
            'province' => 'test',
            'country' => 'test',
            'postal_code' => '123123123'
        ],[
            'Authorization' => 'ren'
        ])
        ->assertStatus(201)
        ->assertJson([
            'data' => [
                'street' => 'test',
                'city' => 'test',
                'province' => 'test',
                'country' => 'test',
                'postal_code' => '123123123'
            ]
        ]);
    }

    function testCreateFailed() {
        $this->seed([UserSeeder::class,ContactSeeder::class]);
        $contact = Contact::first();

        $this->post("/api/contacts/{$contact->id}/addresses",[
            'street' => 'test',
            'city' => 'test',
            'province' => 'test',
            'postal_code' => '123123123'
        ],[
            'Authorization' => 'ren'
        ])
        ->assertStatus(400)
        ->assertJson([
            'errors' => [
                'country' => [
                    'The country field is required.'
                ]
            ]
        ]);
    }
    
    function testCreateContactNotFound() {
        $this->seed([UserSeeder::class,ContactSeeder::class]);
        $contact = Contact::first();

        $this->post("/api/contacts/0/addresses",[
            'street' => 'test',
            'city' => 'test',
            'country' => 'test',
            'province' => 'test',
            'postal_code' => '123123123'
        ],[
            'Authorization' => 'ren'
        ])
        ->assertStatus(404)
        ->assertJson([
            'errors' => [
                'message' => [
                    'Not Found'
                ]
            ]
        ]);
    }

}
