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
        Schema::create('san_pham', function (Blueprint $table) {
            $table->id();
            $table->text('ten')->unique();
            $table->text('mota')->nullable();
            $table->string('xuatxu')->nullable();
            $table->string('sanxuat')->nullable();
            $table->text('mediaurl')->nullable();
            $table->enum('trangthai', ['hoat_dong', 'ngung_hoat_dong', 'bi_khoa', 'cho_duyet'])->default('hoat_dong');
            $table->integer('luotxem')->default(0);

            // Khai báo FK trỏ đến thuong_hieu(id)
            $table->foreignId('id_thuonghieu')
                ->constrained('thuong_hieu')
                ->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('san_pham');
    }
};
