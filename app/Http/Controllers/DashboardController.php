<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch countries for the live map
        $countries = Country::all();
        return view('dashboard.index', compact('countries'));
    }
}
