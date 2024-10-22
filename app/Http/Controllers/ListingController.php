<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    // Show all listings
    public function index()
    {
        return view('listings.index', [
            // latest - orders according to the created_at field in the database
            // filter - is a local scope
            // request - retrieves the values of tag and search from the URL
            // paginate - implements pagination on queries
            // apply filtering and pagination
            'listings' => Listing::latest()
                ->filter(request(['tag', 'search']))
                ->paginate(8)
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

        $formFields['user_id'] = Auth::id();

        // save a new model
        Listing::create($formFields);

        return redirect('/')->with('message', 'Job successfully created!');
    }

    // Show Edit Form
    public function edit(Listing $listing)
    {
        if ($listing->user_id != Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        return view('listings.edit', ['listing' => $listing]);
    }

    // Update Listing Data
    public function update(Request $request, Listing $listing)
    {
        if ($listing->user_id != Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $formFields = $request->validate([
            'title' => 'required',
            'logo' => ['image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'company' => ['required', Rule::unique('listings', 'company')->ignore($listing->id)],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // update the model
        $listing->update($formFields);

        return back()->with('message', 'Job successfully updated!');
    }

    // Delete Listing
    public function destroy(Listing $listing)
    {
        if ($listing->user_id != Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $listing->delete();
        return redirect('/')->with('message', 'Job successfully deleted!');
    }

    // Manage Listings
    public function manage()
    {
        return view('listings.manage', ['listings' => Auth::user()->listings()->get()]);
    }
}
