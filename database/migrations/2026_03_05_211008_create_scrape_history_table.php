<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scraped_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained()->onDelete('cascade');
            $table->string('author');
            $table->text('content');
            $table->string('item_name')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamp('scraped_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrape_history');
    }
};
