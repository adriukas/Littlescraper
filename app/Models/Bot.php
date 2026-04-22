<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bot extends Model
{
    protected $table = 'bots';

    protected $fillable = [
    'name', 'type', 'token', 'discord_channel_id', 'last_scraped_at'
    ];

    public function scrapedData()
    {
        return $this->hasMany(ScrapedData::class);
    }
}