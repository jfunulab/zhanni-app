<?php

namespace Tests\Feature\Countries;

use Domain\Countries\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetCountryListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_get_list_of_all_countries()
    {
        $this->withoutExceptionHandling();

        Country::factory()->count(2)->create();

        $response = $this->getJson("/api/countries");

        $response->assertSuccessful();

        tap($response->decodeResponseJson(), function($response){
            $this->assertCount(2, $response['data']);
        });
    }
}
