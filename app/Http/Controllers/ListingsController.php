<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateListingRequest;
use App\Models\CarModel;
use App\Models\Listing;
use App\Models\Maker;
use Illuminate\Http\Request;

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
        return redirect()->route('listings');
    }
}
