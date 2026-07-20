<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsSentiment extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $guarded = [];

    public function newsArticle()
    {
        return $this->belongsTo(NewsArticle::class);
    }
}
