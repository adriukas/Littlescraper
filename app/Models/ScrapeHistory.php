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

    public static function getAllHistory()
    {
        return self::with('bot')->orderBy('created_at', 'desc')->get();
    }

    public static function getById(int $id): self
    {
        return self::findOrFail($id);
    }

    public static function createRecord(array $data): self
    {
        return self::create($data);
    }

    public function deleteRecord(): bool
    {
        return (bool) $this->delete();
    }
}
