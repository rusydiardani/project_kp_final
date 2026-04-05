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
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropForeign(['service_type_id']);
            $table->dropColumn('service_type_id');
        });
        
        Schema::dropIfExists('service_types');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sla_days')->comment('Estimasi hari pengerjaan');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('service_requests', function (Blueprint $table) {
            $table->foreignId('service_type_id')->constrained();
        });
    }
};
