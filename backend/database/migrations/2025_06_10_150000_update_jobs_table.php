<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['salary_min', 'salary_max', 'benefits', 'is_active']);
            
            // Add new columns
            $table->string('salary_range')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Revert changes
            $table->dropColumn(['salary_range', 'status']);
            
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->text('benefits')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }
}; 