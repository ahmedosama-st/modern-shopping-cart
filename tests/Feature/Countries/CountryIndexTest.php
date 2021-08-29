<?php

namespace Tests\Feature\Countries;

use Tests\TestCase;
use App\Models\Country;

class CountryIndexTest extends TestCase
{
    public function test_it_returns_countries()
    {
        $country = Country::factory()->create();

        $this->json('GET', 'api/countries')
            ->assertJsonFragment([
                'id' => $country->id
            ]);
    }
}
