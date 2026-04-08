<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class ScrapedData extends Model
{
    use HasFactory; 

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