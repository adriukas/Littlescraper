<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapeHistory extends Model
{
    protected $table = 'scrape_history';

    protected $fillable = [
        'bot_id', 'records_found', 'status', 'execution_time', 'error_log', 'request_ip'
    ];

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }
}
