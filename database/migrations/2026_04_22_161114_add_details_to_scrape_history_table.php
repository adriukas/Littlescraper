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
        Schema::table('scrape_history', function (Blueprint $table) {
            $table->float('execution_time')->nullable()->after('status'); // Savybė #6
            $table->text('error_log')->nullable()->after('execution_time'); // Savybė #7
            $table->string('request_ip')->nullable()->after('error_log');  // Savybė #8
        });
    }

    public function down(): void
    {
        Schema::table('scrape_history', function (Blueprint $table) {
            $table->dropColumn(['execution_time', 'error_log', 'request_ip']);
        });
    }
};
