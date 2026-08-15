<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['donors', 'hospitals', 'admins'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->string('locale', 5)->default('en')->after('email');
            });
        }
    }

    public function down(): void
    {
        foreach (['donors', 'hospitals', 'admins'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('locale');
            });
        }
    }
};
