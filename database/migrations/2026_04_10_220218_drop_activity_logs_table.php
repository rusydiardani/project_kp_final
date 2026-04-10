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
        Schema::dropIfExists('activity_logs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('model_type')->nullable(); // Model apa yang terpengaruh
            $table->unsignedBigInteger('model_id')->nullable(); // ID model
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Siapa pelakunya
            $table->string('action'); // Nama aksi (contoh: 'pickup_ktp')
            $table->text('description')->nullable(); 
            $table->timestamps();
        });
    }
};
