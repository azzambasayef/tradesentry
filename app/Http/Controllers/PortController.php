<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Port;

class PortController extends Controller
{
    public function index()
    {
        // View for the Leaflet dashboard
        return view('ports.index');
    }

    public function api()
    {
        // The REST API endpoint returning port data in JSON format
        $ports = Port::with('country.riskScore')->get();
        return response()->json($ports);
    }

    public function apiShips()
    {
        $ships = \App\Models\Ship::with(['originPort', 'destinationPort'])->get();
        return response()->json($ships);
    }
}
