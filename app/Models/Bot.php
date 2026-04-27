<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bot extends Model
{
    use HasFactory;

    protected $table = 'bots';

    protected $fillable = [
        'name', 'type', 'token', 'discord_channel_id', 'last_scraped_at'
    ];

    protected $casts = [
        'last_scraped_at' => 'datetime',
    ];

    public function scrapedData()
    {
        return $this->hasMany(ScrapedData::class);
    }

    public static function getAllBots()
    {
        return self::all();
    }

    public static function getBotById(int $id): self
    {
        return self::findOrFail($id);
    }

    public static function createBot(array $data): self
    {
        return self::create($data);
    }

    public function updateBot(array $data): bool
    {
        return $this->update($data);
    }

    public function deleteBot(): bool
    {
        return (bool) $this->delete();
    }
}
