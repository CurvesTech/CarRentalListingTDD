<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class DeleteListingFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_not_logged_in_user_cannot_delete_a_listing() {
        $response = $this->delete(route('listings.destroy', 1));
        $response->assertRedirect('/login');
    }

    public function test_a_logged_in_user_cannot_delete_the_listing_of_another_user() {
        $listing = Listing::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->delete(route('listings.destroy', $listing->id));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_a_logged_in_user_can_delete_their_listing() {
        $listing = Listing::factory()->create();
        
        $this->actingAs($listing->user);

        $response = $this->delete(route('listings.destroy', $listing->id));

        $response->assertRedirect(route('listings.index'));
        $listing = Listing::find($listing->id);
        $this->assertNull($listing);
    }
}
