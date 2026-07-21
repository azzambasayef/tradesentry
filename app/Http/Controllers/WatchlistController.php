<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        // Get user's watchlisted countries
        $watchlists = Auth::user()->watchlists()->with('country')->get();
        // Extract the countries from watchlists
        $countries = $watchlists->pluck('country');
        
        return view('watchlist.index', compact('countries'));
    }

    public function toggle(Request $request, $country_id)
    {
        $user = Auth::user();
        $watchlist = Watchlist::where('user_id', $user->id)
                              ->where('country_id', $country_id)
                              ->first();

        if ($watchlist) {
            // Already favorited, so remove it
            $watchlist->delete();
            return response()->json(['status' => 'removed']);
        } else {
            // Add to favorites
            Watchlist::create([
                'user_id' => $user->id,
                'country_id' => $country_id
            ]);
            return response()->json(['status' => 'added']);
        }
    }
}
