<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Không cần tạo bảng mới ở đây, chỉ dùng để drop cũ
    }

    public function down(): void
    {
        // rollback không làm gì
    }

    public function reset(): void
    {
        // Artisan::call('migrate:reset');
        // app(\Database\Migrations\ResetAllTables::class)->reset();
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        // DROP theo thứ tự phụ → chính
        $tables = [
            'bienthe_sp',
            'chitiet_giohang',
            'gio_hang',
            // thêm các bảng khác nếu cần
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
            }
        }

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
    }
};
