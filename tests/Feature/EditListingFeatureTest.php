<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditListingFeatureTest extends TestCase
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

    public function test_a_not_logged_in_user_cannot_access_the_edit_listing_form() {
        $response = $this->get(route('listings.edit', 1));
        $response->assertRedirect('/login');
    }

    public function test_a_logged_in_user_can_access_the_edit_listing_form_and_see_the_necessary_fields() {
        $user = User::factory()->create();
        $listing = Listing::factory()->create([
            'user_id' => $user
        ]);
        $this->actingAs($user);
        $response = $this->get(route('listings.edit', $listing));
        $response->assertStatus(200);
        $response->assertSee([
            'Edit Listing', 'Title', 'Maker', 'Model', 'Year', 'Registration Number', 'Transmission', 'Price per day', 'Phone Number', 'Images', 'Select Images'
        ]);
        $response->assertSeeHtml(['name="title"', 'name="maker_id"', 'name="model_id"', 'name="year"', 'name="registration_number"', 'name="transmission"', 'name="price_per_day"', 'name="phone_number"', 'name="images[]']);
        $response->assertSeeHtml(['action="' . route('listings.update', $listing->id) . '" method="POST"']);
        $response->assertSeeHtml('name="_method" value="put"');
    }

    public function test_existing_values_are_prepopulated_when_the_page_is_loaded() {
        $user = User::factory()->create();
        $listing = Listing::factory()->create([
            'user_id' => $user
        ]);
        $this->actingAs($user);
        $response = $this->get(route('listings.edit', $listing));
        $response->assertStatus(200);

        $response->assertSee('Edit Listing');
        $response->assertSeeHtml('value="' . $listing->title . '"');
        $response->assertSeeHtml('value="' . $listing->maker_id . '"  selected');
        $response->assertSeeHtml(':value="model.id" :selected="model.id == ' . $listing->model_id . '"');
        $response->assertSeeHtml('value="' . $listing->year . '"  selected');
        $response->assertSeeHtml('value="' . $listing->transmission . '"  selected');
        $response->assertSeeHtml('value="' . $listing->registration_number . '"');
        $response->assertSeeHtml('value="' . $listing->price_per_day . '"');
        $response->assertSeeHtml('value="' . $listing->phone_number . '');
    }

    public function test_a_not_logged_in_user_cannot_edit_a_listing() {
        $response = $this->put(route('listings.update', 3), []);
        $response->assertRedirect('/login');
    }

    public function test_a_logged_in_user_cannot_access_the_edit_form_of_a_listing_of_another_user() {
        $listing = Listing::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user);

       $response = $this->get(route('listings.edit', $listing));

       $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_a_logged_in_user_can_successfully_edit_their_listing() {
        Storage::fake('images');

        $data = $this->data();

        $user = User::factory()->create();
        $listing = Listing::factory()->create([
            'user_id' => $user->id
        ]);
        $this->actingAs($user);

        $response = $this->put(route('listings.update', $listing), $data);

        $response->assertRedirect(route('listings.index'));

        $listing->refresh();
        
        $this->assertNotNull($listing);
        $this->assertEquals('My Car', $listing->title);
        $this->assertEquals(1, $listing->maker_id);
        $this->assertEquals(1, $listing->model_id);
        $this->assertEquals(2020, $listing->year);
        $this->assertEquals('ABC123', $listing->registration_number);
        $this->assertEquals('automatic', $listing->transmission);
        $this->assertEquals(100, $listing->price_per_day);
        $this->assertEquals('1234567890', $listing->phone_number);

        return $listing;
    }

    public function test_existing_images_are_kept_if_no_new_images_are_uploaded() {
        Storage::fake('images');

        $data = $this->data();
        unset($data['images']);

        $user = User::factory()->create();
        $listing = Listing::factory()->create([
            'user_id' => $user->id
        ]);
        $beforeImageCount = $listing->images->count();
        $this->actingAs($user);

        $this->put(route('listings.update', $listing), $data);

        $listing->refresh();

        $this->assertEquals($listing->images->count(), $beforeImageCount);

    }

    public function test_new_images_uploaded_replace_the_existing_images() {

        Storage::fake('images');

        $data = $this->data();

        $user = User::factory()->create();
        $listing = Listing::factory()->create([
            'user_id' => $user->id
        ]);


        $this->actingAs($user);

        $this->put(route('listings.update', $listing), $data);
        

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


    public function test_a_title_is_required_to_edit_a_listing() {

        $data = $this->data();
        unset($data['title']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('listings.update', Listing::factory()->create([
            'user_id' => $user->id
        ])), $data);


        $response->assertSessionHasErrors('title');
    }

    public function test_a_maker_id_is_required_to_edit_a_listing() {
        $data = $this->data();
        unset($data['maker_id']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('listings.update', Listing::factory()->create([
            'user_id' => $user->id
        ])), $data);

        $response->assertSessionHasErrors('maker_id');
    }

    public function test_maker_must_exist_for_maker_id_provided_to_create_the_listing() {
        $data = $this->data();
        $data['maker_id'] = 243443;

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('listings.update', Listing::factory()->create([
            'user_id' => $user->id
        ])), $data);

        $response->assertSessionHasErrors('maker_id');
    }

    public function test_a_model_id_is_required_to_edit_a_listing() {
        $data = $this->data();
        unset($data['model_id']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('listings.update', Listing::factory()->create([
            'user_id' => $user->id
        ])), $data);

        $response->assertSessionHasErrors('model_id');
    }

    public function test_model_must_exist_for_model_id_provided_to_create_the_listing() {
        $data = $this->data();
        $data['model_id'] = 243443;

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('listings.update', Listing::factory()->create([
            'user_id' => $user->id
        ])), $data);

        $response->assertSessionHasErrors('model_id');
    }

    public function test_a_year_is_required_to_edit_a_listing() {
        $data = $this->data();
        unset($data['year']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('listings.update', Listing::factory()->create([
            'user_id' => $user->id
        ])), $data);

        $response->assertSessionHasErrors('year');
    }

    public function test_a_registration_number_is_required_to_edit_a_listing() {
        $data = $this->data();
        unset($data['registration_number']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('listings.update', Listing::factory()->create([
            'user_id' => $user->id
        ])), $data);

        $response->assertSessionHasErrors('registration_number');
    }

    public function test_a_transmission_is_required_to_edit_a_listing() {
        $data = $this->data();
        unset($data['transmission']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('listings.update', Listing::factory()->create([
            'user_id' => $user->id
        ])), $data);

        $response->assertSessionHasErrors('transmission');
    }

    public function test_a_price_per_day_is_required_to_edit_a_listing() {
        $data = $this->data();
        unset($data['price_per_day']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('listings.update', Listing::factory()->create([
            'user_id' => $user->id
        ])), $data);

        $response->assertSessionHasErrors('price_per_day');
    }

    public function test_a_phone_number_is_required_to_edit_a_listing() {
        $data = $this->data();
        unset($data['phone_number']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('listings.update', Listing::factory()->create([
            'user_id' => $user->id
        ])), $data);

        $response->assertSessionHasErrors('phone_number');
    }

    public function test_image_is_not_required_to_edit_a_listing() {
        $data = $this->data();
        unset($data['images']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('listings.update', Listing::factory()->create([
            'user_id' => $user->id
        ])), $data);

        $response->assertSessionHasNoErrors();
    }
}
