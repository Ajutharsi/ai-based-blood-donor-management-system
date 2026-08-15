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
    Schema::create('blood_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
        $table->string('blood_group');
        $table->integer('units_needed')->default(1);
        $table->enum('urgency', ['standard', 'urgent', 'critical'])->default('standard');
        $table->string('ward')->nullable();
        $table->date('required_by')->nullable();
        $table->text('notes')->nullable();
        $table->enum('status', ['pending', 'fulfilled', 'cancelled'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
