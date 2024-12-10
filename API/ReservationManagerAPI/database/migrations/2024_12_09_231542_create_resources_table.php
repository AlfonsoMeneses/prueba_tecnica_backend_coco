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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25);
            $table->string('description', 45)->nullable();
            $table->integer('capacity');
            $table->foreignId('resource_type_id')->constrained('resource_types')->onDelete('restrict');
            $table->timestamps();
            $table->boolean('active')->default(true);;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
