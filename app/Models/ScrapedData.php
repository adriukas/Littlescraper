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
        'scraped_at',
        'item_name'
    ];

    protected $casts = [
        'scraped_at' => 'datetime',
        'price' => 'float',
    ];

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }

    public static function getAllByBot(int $botId)
    {
        return self::where('bot_id', $botId)->orderBy('scraped_at', 'desc')->get();
    }

    public static function getById(int $id): self
    {
        return self::findOrFail($id);
    }

    public static function createRecord(array $data): self
    {
        return self::create($data);
    }

    public function updateRecord(array $data): bool
    {
        return $this->update($data);
    }

    public function deleteRecord(): bool
    {
        return (bool) $this->delete();
    }
}
