<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

use App\Models\ExchangeRate;
use App\Models\NewsArticle;

class DashboardController extends Controller
{
    public function index()
    {
        $topCurrencies = ExchangeRate::whereIn('target_currency', ['EUR', 'GBP', 'JPY', 'IDR', 'CNY', 'AUD', 'SGD'])->get();
        $latestNews = NewsArticle::with('country')->latest()->take(5)->get();
        return view('dashboard.index', compact('topCurrencies', 'latestNews'));
    }
}
