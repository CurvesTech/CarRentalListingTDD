<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateListingRequest;
use App\Http\Requests\EditListingRequest;
use App\Models\CarModel;
use App\Models\Listing;
use App\Models\Maker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ListingsController extends Controller
{
    public function create() {
        $makers = Maker::all();
        $models = CarModel::all();
        return view('listings.create', [
            'makers' => $makers,
            'models' => $models
        ]);
    }

    public function store(CreateListingRequest $request) {
        $listing = $request->user()->listings()->create($request->except('images'));
        foreach($request->images as $image) {
            $path = $image->store('images', 'public');
            $listing->images()->create(['path' => $path]);
        }
        return redirect()->route('listings.index');
    }

    public function index(Request $request) {
        return view('listings/index', [
            'listings' => $request->user()->listings
        ]);
    }

    public function edit(Listing $listing) {
        Gate::authorize('edit', $listing);
        $makers = Maker::all();
        $models = CarModel::all();

        return view('listings/edit', [
            'listing' => $listing,
            'makers' => $makers,
            'models' => $models
        ]);
    }

    public function update(EditListingRequest $request, Listing $listing) {
        $listing->update($request->except('images'));

        if($request->has('images')) {
            $listing->images()->delete();
            foreach($request->images as $image) {
                $path = $image->store('images', 'public');
                $listing->images()->create(['path' => $path]);
            }
        }
        
        return redirect()->route('listings.index');
    }

    public function destroy(Listing $listing) {
        Gate::authorize('delete', $listing);
        $listing->images()->delete();
        $listing->delete();
        return redirect()->route('listings.index');
    }
}
