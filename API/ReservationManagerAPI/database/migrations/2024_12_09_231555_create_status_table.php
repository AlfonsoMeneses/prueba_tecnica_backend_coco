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
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10);
            $table->string('name', 45);
            $table->timestamps();
            $table->boolean('active')->default(true);;
        });

        // Insertar los estados
        DB::table('status')->insert([
            ['code' => 'PENDING'    ,   'name' => 'Pendiente',  'created_at' => now(), 'updated_at' => now() ],
            ['code' => 'CONFIRMED'  ,   'name' => 'Confirmado', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CANCELLED'  ,   'name' => 'Cancelado' , 'created_at' => now(), 'updated_at' => now() ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status');
    }
};
