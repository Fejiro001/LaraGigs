<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Collection;

class ListingController extends Controller
{
    // Show all listings
    public function index()
    {
        return view('listings.index', [
            // latest - sorts according to the created_at field in the database
            // filter - a local scope
            // request - retrieves the values of tag and search from the URL
            // apply filtering
            'listings' => Listing::latest()
                ->filter(request(['tag', 'search']))
                ->paginate(10)
        ]);
    }

    // Show a single listing
    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    // Show Create Form
    public function create()
    {
        return view('listings.create');
    }

    // Store Listing Data
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // save a new model
        Listing::create($formFields);

        return redirect('/')->with('success', 'Job successfully created!');
    }
}
