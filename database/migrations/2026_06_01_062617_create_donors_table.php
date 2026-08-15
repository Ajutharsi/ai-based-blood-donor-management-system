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
    Schema::create('donors', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('phone')->nullable();
        $table->date('date_of_birth')->nullable();
        $table->integer('age')->nullable();
        $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
        $table->string('nic')->nullable()->unique();
        $table->string('blood_group')->nullable();
        $table->decimal('weight_kg', 5, 2)->nullable();
        $table->decimal('hemoglobin', 4, 1)->nullable();
        $table->integer('total_donations')->default(0);
        $table->date('last_donation_date')->nullable();
        $table->string('city')->nullable();
        $table->string('district')->nullable();
        $table->string('donation_center')->nullable();
        $table->text('medical_notes')->nullable();
        $table->boolean('is_eligible')->default(false);
        $table->rememberToken();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};
