<?php

// database/migrations/xxxx_xx_xx_create_danh_muc_table.php

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
        Schema::create('danh_muc', function (Blueprint $table) {
            $table->id();
            $table->string('ten')->unique();

            $table->enum('trangthai', ['hoat_dong', 'ngung_hoat_dong', 'bi_khoa', 'cho_duyet'])->default('hoat_dong');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_muc');
    }
};
