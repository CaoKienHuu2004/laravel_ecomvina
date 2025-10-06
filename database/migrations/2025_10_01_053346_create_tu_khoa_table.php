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
        Schema::create('tu_khoa', function (Blueprint $table) {
            $table->id();
            $table->text('dulieu');
            $table->integer('soluot')->default(0);
            $table->timestamps();
            $table->fullText('dulieu'); // tạo fulltext index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tu_khoa');
    }
};
