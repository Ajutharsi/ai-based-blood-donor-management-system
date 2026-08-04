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
        Schema::create('blood_inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_inventory_id')->constrained('blood_inventory')->cascadeOnDelete();
            $table->foreignId('hospital_id')->constrained()->cascadeOnDelete();
            $table->string('blood_group');
            $table->enum('action', ['add', 'remove', 'threshold_update', 'fulfillment']);
            $table->integer('units_before');
            $table->integer('units_after');
            $table->string('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['hospital_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_inventory_logs');
    }
};
