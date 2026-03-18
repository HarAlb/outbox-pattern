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
        Schema::create('outbox_messages', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->json('payload');
            $table->boolean('processed')->default(false);
            $table->boolean('failed')->default(false);
            $table->tinyInteger('attempts')->unsigned()->default(0);
            $table->text('error')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->string('correlation_id', 100)->nullable();
            $table->string('aggregate_id', 100)->nullable();
            $table->timestamps();

            $table->index(['processed', 'attempts', 'created_at'], 'outbox_pending_idx');
            $table->index(['failed', 'updated_at'], 'outbox_failed_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbox_messages');
    }
};
