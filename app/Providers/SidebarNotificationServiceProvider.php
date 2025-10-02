<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\MonitoringBarang;
use App\Models\MonitoringPengadaan;

class SidebarNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share notification counts with admin layout
        View::composer('layouts.admin', function ($view) {
            $notifications = [
                'monitoring_pengambilan' => $this->getPendingPengambilan(),
                'monitoring_pengadaan' => $this->getPendingPengadaan(),
            ];

            $view->with('notifications', $notifications);
        });
    }

    /**
     * Get count of pending pengambilan (diajukan status)
     */
    private function getPendingPengambilan()
    {
        try {
            return MonitoringBarang::where('status', 'diajukan')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get count of pending pengadaan (proses status)
     */
    private function getPendingPengadaan()
    {
        try {
            return MonitoringPengadaan::where('status', 'proses')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
