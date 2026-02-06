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
        Schema::table('employees', function (Blueprint $table): void {
            $table->foreignId('created_by')
                ->nullable()
                ->after('phone_number')
                ->constrained('users')
                ->nullOnDelete();
        });

        Schema::table('attendances', function (Blueprint $table): void {
            $table->foreignId('user_id')
                ->nullable()
                ->after('employee_id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('created_by');
        });

        Schema::table('attendances', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};

