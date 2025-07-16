<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pamphlets', function (Blueprint $table) {
            $table->string('audio_path')->nullable()->after('html_content');
        });
    }

    public function down()
    {
        Schema::table('pamphlets', function (Blueprint $table) {
            $table->dropColumn('audio_path');
        });
    }
};