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
    Schema::table('donors', function (Blueprint $table) {
        $table->decimal('response_probability', 5, 2)->default(0)->after('ai_confidence');
        $table->string('response_level')->default('medium')->after('response_probability');
        $table->boolean('is_anomaly')->default(false)->after('response_level');
        $table->decimal('anomaly_score', 5, 2)->default(0)->after('is_anomaly');
    });
}

public function down(): void
{
    Schema::table('donors', function (Blueprint $table) {
        $table->dropColumn([
            'response_probability',
            'response_level',
            'is_anomaly',
            'anomaly_score',
        ]);
    });
}

};
