<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExchangeRate;
use App\Models\CurrencyHistory;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        // Get all latest rates
        $rates = ExchangeRate::orderBy('target_currency')->get();
        
        // Pick top currencies for chart (EUR, GBP, JPY, IDR, CNY, AUD)
        $topCurrencies = ['EUR', 'GBP', 'JPY', 'IDR', 'CNY', 'AUD'];
        
        // Fetch 30-day historical data for top currencies
        $historyData = [];
        $dates = [];
        
        foreach ($topCurrencies as $currency) {
            $history = CurrencyHistory::where('target_currency', $currency)
                ->orderBy('date', 'asc')
                ->get();
                
            if ($history->count() > 0) {
                if (empty($dates)) {
                    $dates = $history->pluck('date')->toArray();
                }
                $historyData[$currency] = $history->pluck('rate')->toArray();
            }
        }
        
        // Add current selected currency for detail chart
        $selectedCurrency = $request->query('currency', 'IDR');
        $selectedHistory = CurrencyHistory::where('target_currency', $selectedCurrency)
            ->orderBy('date', 'asc')
            ->get();
            
        $selectedHistoryData = $selectedHistory->pluck('rate')->toArray();
        $selectedCurrentRate = ExchangeRate::where('target_currency', $selectedCurrency)->first();

        return view('currencies.index', compact('rates', 'topCurrencies', 'dates', 'historyData', 'selectedCurrency', 'selectedHistoryData', 'selectedCurrentRate'));
    }
}
