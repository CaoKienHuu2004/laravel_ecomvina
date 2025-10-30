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
        Schema::create('tukhoa', function (Blueprint $table) {
            $table->increments('id'); // Khóa chính tự tăng
            $table->text('tukhoa');   // Từ khóa
            $table->integer('luottruycap'); // Số lượt truy cập
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tukhoa');
    }
};
