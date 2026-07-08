<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return view('countries.index', compact('countries'));
    }

    public function show($id)
    {
        $country = Country::findOrFail($id);
        return view('countries.show', compact('country'));
    }
}
