<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexListingFeatureTest extends TestCase
{
    use RefreshDatabase;
    public function test_a_not_logged_in_user_cannot_view_the_users_listing_page() {
        $response = $this->get(route('listings.index'));
        $response->assertRedirect('/login');
    }

    public function test_a_logged_in_user_can_view_the_users_listing_page() {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('listings.index'));
        $response->assertOk();
    }

    public function test_a_logged_in_user_can_view_the_users_listing_page_with_listings() {
        $user = User::factory()->create();
        $listings = Listing::factory(3)->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->get(route('listings.index'));
        $response->assertOk();
        $response->assertSee($listings[0]->title);
        $response->assertSee($listings[1]->title);
        $response->assertSee($listings[2]->title);
    }

    public function test_a_logged_in_user_can_view_the_users_listing_page_with_no_listings() {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('listings.index'));
        $response->assertOk();
        $response->assertSee('You have no listings');
    }

    public function test_can_see_the_button_to_edit_a_listing_on_index_page() {
        $user = User::factory()->create();
        $listing = Listing::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->get(route('listings.index'));
        $response->assertOk();
        $response->assertSee('Edit');
        $response->assertSee(route('listings.edit', $listing));
    }

    public function test_can_see_the_button_to_delete_a_listing_on_index_page() {
        $user = User::factory()->create();
        $listing = Listing::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->get(route('listings.index'));
        $response->assertOk();
        $response->assertSee('Delete');
        $response->assertSee(route('listings.destroy', $listing));
    }
}
