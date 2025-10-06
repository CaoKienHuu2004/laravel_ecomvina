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
        Schema::create('banner_quangcao', function (Blueprint $table) {
            $table->id();
            $table->string('vitri');
            $table->string('hinhanh');
            $table->string('duongdan');
            $table->mediumtext('tieude');
            $table->enum('trangthai',['hoat_dong','ngung_hoat_dong'])->default('hoat_dong');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_quangcao');
    }
};
