<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;

use App\Models\GiohangModel;
use App\Observers\GioHangObserver;

use App\Models\DonhangModel;
use App\Observers\DonhangObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Khởi động bất kỳ dịch vụ ứng dụng nào.

     */
    public function boot(): void
    {
        // GiohangModel::observe(GioHangObserver::class); // nếu muốn thay cho trigger database
        DonhangModel::observe(DonhangObserver::class); // đang dùng thay cho trigger database, database trigger không cũng kì lắm làm cho đa dạng

    }
}
