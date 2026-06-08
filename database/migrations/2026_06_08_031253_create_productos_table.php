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
    Schema::create('productos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('vendedor'); // <-- Esta es la columna que MySQL Workbench está pidiendo a gritos
        $table->decimal('precio', 8, 2);
        $table->integer('stock')->default(10);
        $table->string('imagen')->nullable();
        $table->text('descripcion')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
