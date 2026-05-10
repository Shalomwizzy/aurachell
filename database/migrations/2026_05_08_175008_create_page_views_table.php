<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('url', 500);
            $table->string('referrer', 500)->nullable();
            $table->string('ip_hash', 64);
            $table->string('session_id', 64);
            $table->enum('device', ['desktop', 'mobile', 'tablet'])->default('desktop');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('created_at');

            $table->index('created_at');
            $table->index('url');
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
