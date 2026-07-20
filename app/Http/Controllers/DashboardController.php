<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }
}
