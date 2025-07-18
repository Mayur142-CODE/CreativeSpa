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
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('validity_count')->after('price')->nullable();
            $table->string('validity_unit')->after('validity_count')->nullable()->comment('day, week, month, year');
            // $table->decimal('total', 10, 2);
        });

        Schema::table('package_service', function (Blueprint $table) {
            $table->decimal('total', 10, 2)->after('therapy_id');
            $table->integer('qty')->after('total');
            // $table->decimal('discount', 5, 2)->after('qty')->comment('Discount in percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['validity_count', 'validity_unit']);
        });

        Schema::table('package_service', function (Blueprint $table) {
            $table->dropColumn([ 'qty']);
        });
    }
};
