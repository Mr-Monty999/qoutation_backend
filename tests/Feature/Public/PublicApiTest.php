<?php

namespace Tests\Feature\Public;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_anyone_can_get_activities()
    {

        $response = $this->get('/api/v1/public/activities');

        $response->assertStatus(200);
    }
    public function test_anyone_can_get_countries()
    {

        $response = $this->get('/api/v1/public/countries');

        $response->assertStatus(200);
    }
}
