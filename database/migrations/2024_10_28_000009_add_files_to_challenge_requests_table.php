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
        Schema::table('challenge_requests', function (Blueprint $table) {
            $table->string('hint_file')->nullable()->after('hint');
            $table->string('original_hint_filename')->nullable()->after('hint_file');
            $table->string('challenge_file')->nullable()->after('original_hint_filename');
            $table->string('original_challenge_filename')->nullable()->after('challenge_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenge_requests', function (Blueprint $table) {
            $table->dropColumn([
                'hint_file',
                'original_hint_filename',
                'challenge_file',
                'original_challenge_filename',
            ]);
        });
    }
};
