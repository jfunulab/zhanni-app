<?php

namespace Tests\Feature\Countries;

use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetCountryStateListsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_get_list_of_all_countries()
    {
        $this->withoutExceptionHandling();

        $country = Country::factory()->create();
        CountryState::factory()->count(2)->create(['country_id' => $country->id]);

        $response = $this->getJson("/api/countries/$country->id/states");

        $response->assertSuccessful();

        tap($response->decodeResponseJson(), function($response){
            $this->assertCount(2, $response['data']);
        });
    }
}
