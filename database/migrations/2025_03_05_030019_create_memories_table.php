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
        Schema::create('memories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->nullable(false)
                ->constrained()->cascadeOnDelete();
            $table->foreignId('mask_id')->nullable()
                ->constrained()->cascadeOnDelete();
            $table->string('title')->nullable(false);
            $table->text('content')->nullable(false);
            $table->string('type', 20)->nullable(false)->index();
            $table->jsonb('meta')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memories');
    }
};
