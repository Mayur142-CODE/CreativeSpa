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
        Schema::create('receipt_package_therapies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receipt_id'); // Links to receipts table
            $table->unsignedBigInteger('package_id'); // Links to packages table
            $table->unsignedBigInteger('therapy_id'); // Links to therapies table
            $table->unsignedBigInteger('therapist_id');
            $table->decimal('original_qty', 10, 2); // Original quantity
            $table->decimal('redeem_qty', 10, 2)->nullable(); // Redeemed quantity
            $table->decimal('price', 10, 2); // Price per unit
            // $table->decimal('discount', 10, 2)->default(0); // Discount applied
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->date('date')->nullable();
            $table->decimal('total', 10, 2); // Final total after discount

            // Foreign keys
            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('therapy_id')->references('id')->on('therapies')->onDelete('cascade');

            $table->foreign('therapist_id')->references('id')->on('therapists')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_package_therapies');
    }
};
