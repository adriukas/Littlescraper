<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    protected $table = 'bots';

    protected $fillable = [
        'name',
        'discord_channel_id'
    ];

    public function scrapedData()
    {
        return $this->hasMany(ScrapedData::class);
    }
}