<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    //Show All Listing
    public function index() {
        return view('listings.index', [
            'listing' => Listing::latest()->filter(request(['tag', 'search']))->paginate(4)
        ]);
    }

    //Show Single Listing
    public function show(Listing $listing) {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }
    //Show Create Form
    public function create() {
        return view('listings.create');
    }

    //Store Listing Data
    public function store(Request $request) {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        return redirect('/')->with('message', 'Listing created successfully!');
    }
    //Update Listing Data
    public function update(Request $request, Listing $listing) {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        $listing->update($formFields);

        return back()->with('message', 'Listing updated successfully!');
    }

    // Show Edit Form
    public function edit (Listing $listing) {
        return view('listings.edit', ['listing' => $listing]);
    }

    //Delete Listing
    public function delete(Listing $listing) {
        $listing->delete();
    return redirect('/')->with('message', 'Listing deleted sucsessfully!');
    }

}
