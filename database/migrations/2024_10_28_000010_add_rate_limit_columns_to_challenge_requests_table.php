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
            $table->string('submission_ip', 45)->nullable()->after('suggested_flag')->index();
            $table->string('device_fingerprint', 100)->nullable()->after('submission_ip')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenge_requests', function (Blueprint $table) {
            $table->dropColumn(['submission_ip', 'device_fingerprint']);
        });
    }
};
