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
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('phone')->nullable()->after('email'); // Phone as bigInteger
            $table->text('address')->nullable()->after('phone');
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->after('address');
            $table->string('profile_picture')->nullable()->after('status');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete()->after('profile_picture');
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['role_id']);
            $table->dropColumn(['phone', 'address', 'status', 'profile_picture', 'branch_id','role_id']);
        });
    }
};
