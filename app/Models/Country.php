<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $guarded = [];

    public function newsArticles()
    {
        return $this->hasMany(NewsArticle::class);
    }

    public function riskScore()
    {
        return $this->hasOne(RiskScore::class);
    }
}
