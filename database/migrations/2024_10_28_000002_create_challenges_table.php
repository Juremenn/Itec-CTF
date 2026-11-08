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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('category', ['web', 'crypto', 'recon']);
            $table->enum('difficulty', ['easy', 'medium', 'hard']);
            $table->integer('points')->default(100);
            $table->string('flag');
            $table->text('description');
            $table->text('hint')->nullable();
            $table->text('remediation')->nullable();
            $table->enum('mode_target', ['competition', 'training', 'both'])->default('both');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};

