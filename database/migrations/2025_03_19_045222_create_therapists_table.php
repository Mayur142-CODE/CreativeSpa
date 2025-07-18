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
        Schema::create('therapists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->date('dob')->nullable(); // Date of Birth
            $table->string('contact')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('address')->nullable();
            $table->string('designation')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('fixed_salary', 10, 2)->nullable(); // Fixed Salary
            $table->decimal('working_hours_or_days', 5, 2)->nullable(); // Value for working hours or days
            $table->enum('working_hours_type', ['Hours', 'Days'])->default('Hours'); // Whether it is in Hours or Days
            $table->integer('holidays')->nullable(); // Number of Holidays
            $table->enum('payroll_calculation', ['Fixed', 'Hourly', 'Commission'])->default('Fixed');
            $table->string('profile_picture')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('therapists');
    }
};
