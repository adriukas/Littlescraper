<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapedData extends Model
{
    protected $table = 'scraped_data';

    protected $fillable = [
        'bot_id',
        'author',
        'content',
        'price',
        'scraped_at'
    ];

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }
}