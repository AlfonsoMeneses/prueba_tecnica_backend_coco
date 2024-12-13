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
        Schema::create('resource_types', function (Blueprint $table) {
            $table->id();
            $table->string('code',10);
            $table->string('name', 45);
            $table->timestamps();
            $table->boolean('active')->default(true);
        });

        // Insertar tipo de recurso 'General'
        DB::table('resource_types')->insert([
            ['code' => 'GEN', 'name' => 'General',  'created_at' => now(), 'updated_at' => now() ]
        ]);
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_types');
    }
};
