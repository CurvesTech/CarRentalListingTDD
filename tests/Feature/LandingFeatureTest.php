<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Listing;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LandingFeatureTest extends TestCase
{

    use RefreshDatabase;

    public function test_anyone_can_view_listings_on_home_page() {
        $listings = Listing::factory()->count(5)->create();
        $response = $this->get(route('home'));
        $response->assertStatus(200);

        $listings->each(function($listing) use ($response) {
            $response->assertSee($listing->title);
            $response->assertSee($listing->price_per_day);
        });
    }
   
}
