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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name',150)->nullable();
            $table->string('email',100)->nullable();
            $table->string('clave',100)->nullable();
            $table->string('phone',20)->nullable();
            $table->integer('user_id')->nullable()->comment('Id de la tabla user');
            $table->integer('user_reg')->nullable()->comment('Usuario quien registro');
            $table->integer('tipo')->comment('1 es admin, 2 peluquero'); //1 si es admin, 2 peluquero
            $table->integer('estado')->nullable()->comment('1=activo, 0=inactivo');
            $table->foreignId('departments_id')
                           ->constrained('departments')
                           ->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
