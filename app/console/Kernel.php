<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\LaporanPenjualanController;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Daftarkan schedule aplikasi (otomatis harian, mingguan, bulanan, tahunan)
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            try {
                LaporanPenjualanController::generateLaporan('harian');
                LaporanPenjualanController::generateLaporan('mingguan');
                LaporanPenjualanController::generateLaporan('bulanan');
                LaporanPenjualanController::generateLaporan('tahunan');
                Log::info('✅ Laporan penjualan otomatis dibuat pada ' . now());
            } catch (\Throwable $e) {
                Log::error('❌ Gagal membuat laporan otomatis: ' . $e->getMessage());
            }
        })->dailyAt('00:05');
    }

    /**
     * Daftarkan command artisan (kalau nanti kamu butuh bikin perintah baru)
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}