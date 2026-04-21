<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        /**
         * Run the migrations.
         */
    public function up()
    {
        Schema::table('bots', function (Blueprint $table) {
            // Adding a nullable token column to store individual bot tokens if needed
            $table->string('token')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
};