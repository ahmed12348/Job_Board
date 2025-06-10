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
        Schema::table('jobs', function (Blueprint $table) {
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->text('benefits')->nullable();
            // Drop the old salary_range column
            $table->dropColumn('salary_range');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('salary_range')->nullable();
            $table->dropColumn(['salary_min', 'salary_max', 'benefits']);
        });
    }
};
