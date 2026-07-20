<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsArticle;
use App\Models\Country;
use Illuminate\Support\Facades\Artisan;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $countryId = $request->query('country_id');
        $search = $request->query('search');
        
        $query = NewsArticle::with(['country', 'sentimentData'])->latest('published_at');
        
        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }
        
        // Use pagination for news articles
        $news = $query->paginate(15)->appends($request->all());
        
        // Get list of countries that have news for the filter dropdown
        $countriesWithNews = Country::whereHas('newsArticles')->orderBy('name')->get();
        
        return view('news.index', compact('news', 'countriesWithNews', 'countryId', 'search'));
    }
    
    public function fetch()
    {
        Artisan::call('fetch:news');
        return redirect()->back()->with('success', 'Global news successfully fetched and analyzed!');
    }
}
