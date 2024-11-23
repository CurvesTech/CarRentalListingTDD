<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateListingFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void 
    {
        parent::setUp();
        $this->seed();
    }

    protected function data(): array 
    {
        return [
            'title' => 'My Car',
            'maker_id' => 1,
            'model_id' => 1,
            'year' => 2020,
            'registration_number' => 'ABC123',
            'transmission' => 'automatic',
            'price_per_day' => 100,
            'phone_number' => '1234567890',
            'images' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg')
            ]
        ];
    }

    public function test_a_not_logged_in_user_cannot_access_the_create_listing_form() {
        $response = $this->get(route('listings.create'));
        $response->assertRedirect('/login');
    }

    public function test_a_logged_in_user_can_access_the_create_listing_form_and_see_the_necessary_fields() {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('listings.create'));
        $response->assertStatus(200);
        $response->assertSee([
            'Create Listing', 'Title', 'Maker', 'Model', 'Year', 'Registration Number', 'Transmission', 'Price per day', 'Phone Number', 'Images', 'Select Images'
        ]);
        $response->assertSeeHtml(['name="title"', 'name="maker_id"', 'name="model_id"', 'name="year"', 'name="registration_number"', 'name="transmission"', 'name="price_per_day"', 'name="phone_number"', 'name="images[]']);
        $response->assertSeeHtml(['action="' . route('listings.store') . '" method="POST"']);
    }

    public function test_a_not_logged_in_user_cannot_create_a_listing() {
        $response = $this->post(route('listings.store'), []);
        $response->assertRedirect('/login');
    }

    public function test_a_logged_in_user_can_successfully_create_a_listing() {
        Storage::fake('images');

        $data = $this->data();

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('listings.store'), $data);

        $response->assertRedirect(route('listings'));

        $listing = Listing::where('user_id', $user->id)
            ->where('title', 'My Car')->first();
        $this->assertNotNull($listing);
        $this->assertEquals('My Car', $listing->title);
        $this->assertEquals(1, $listing->maker_id);
        $this->assertEquals(1, $listing->model_id);
        $this->assertEquals(2020, $listing->year);
        $this->assertEquals('ABC123', $listing->registration_number);
        $this->assertEquals('automatic', $listing->transmission);
        $this->assertEquals(100, $listing->price_per_day);
        $this->assertEquals('1234567890', $listing->phone_number);
        
        Storage::disk('public')->assertExists('images/' . $data['images'][0]->hashName());
        Storage::disk('public')->assertExists('images/' . $data['images'][1]->hashName());
        
        $this->assertCount(count($data['images']), $listing->images);

        $listing->images->each(function($image) use ($data) {
            $this->assertContains($image->path, [
                'images/' . $data['images'][0]->hashName(),
                'images/' . $data['images'][1]->hashName()
            ]);
        });

    }

    public function test_a_title_is_required_to_create_a_listing() {
        $data = $this->data();
        unset($data['title']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('listings.store'), $data);

        $response->assertSessionHasErrors('title');
    }

    public function test_a_maker_id_is_required_to_create_a_listing() {
        $data = $this->data();
        unset($data['maker_id']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('listings.store'), $data);

        $response->assertSessionHasErrors('maker_id');
    }

    public function test_maker_must_exist_for_maker_id_provided_to_create_the_listing() {
        $data = $this->data();
        $data['maker_id'] = 243443;

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('listings.store'), $data);

        $response->assertSessionHasErrors('maker_id');
    }

    public function test_a_model_id_is_required_to_create_a_listing() {
        $data = $this->data();
        unset($data['model_id']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('listings.store'), $data);

        $response->assertSessionHasErrors('model_id');
    }

    public function test_model_must_exist_for_model_id_provided_to_create_the_listing() {
        $data = $this->data();
        $data['model_id'] = 243443;

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('listings.store'), $data);

        $response->assertSessionHasErrors('model_id');
    }

    public function test_a_year_is_required_to_create_a_listing() {
        $data = $this->data();
        unset($data['year']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('listings.store'), $data);

        $response->assertSessionHasErrors('year');
    }

    public function test_a_registration_number_is_required_to_create_a_listing() {
        $data = $this->data();
        unset($data['registration_number']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('listings.store'), $data);

        $response->assertSessionHasErrors('registration_number');
    }

    public function test_a_transmission_is_required_to_create_a_listing() {
        $data = $this->data();
        unset($data['transmission']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('listings.store'), $data);

        $response->assertSessionHasErrors('transmission');
    }

    public function test_a_price_per_day_is_required_to_create_a_listing() {
        $data = $this->data();
        unset($data['price_per_day']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('listings.store'), $data);

        $response->assertSessionHasErrors('price_per_day');
    }

    public function test_a_phone_number_is_required_to_create_a_listing() {
        $data = $this->data();
        unset($data['phone_number']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('listings.store'), $data);

        $response->assertSessionHasErrors('phone_number');
    }

    public function test_atleast_one_image_is_required_to_create_a_listing() {
        $data = $this->data();
        unset($data['images']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('listings.store'), $data);

        $response->assertSessionHasErrors('images');
    }

    
}
