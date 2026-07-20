<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiskScore;
use App\Models\RiskWeight;
use Illuminate\Support\Facades\Artisan;

class RiskController extends Controller
{
    public function index()
    {
        // Get weights
        $weights = RiskWeight::all();
        
        // Fetch all countries ordered by highest risk, we will limit view to 50 via JS
        $risks = RiskScore::with('country')->orderByDesc('total_score')->get();
        
        // Calculate Statistics
        $totalCritical = RiskScore::where('risk_level', 'critical')->count();
        $totalHigh = RiskScore::where('risk_level', 'high')->count();
        $totalMedium = RiskScore::where('risk_level', 'medium')->count();
        $totalLow = RiskScore::where('risk_level', 'low')->count();
        $avgScore = RiskScore::avg('total_score') ?? 0;

        return view('risk.index', compact(
            'weights', 'risks', 'totalCritical', 'totalHigh', 'totalMedium', 'totalLow', 'avgScore'
        ));
    }
    
    // Optional endpoint if the user wants to trigger recalculation manually
    public function recalculate()
    {
        Artisan::call('risk:calculate');
        return redirect()->back()->with('success', 'Risk Engine has recalculated all global supply chain risks!');
    }
}
