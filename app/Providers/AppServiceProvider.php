<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Obat;
use App\Models\NotifikasiObat;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // ← WAJIB

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
     */
    public function boot(): void

    {
        if ($this->app->environment('production')){
            \URL::forceScheme('https');
        }
        
        $obatSemua = Obat::all();

        foreach ($obatSemua as $obat) {
            $exp = Carbon::parse($obat->tanggal_kadaluarsa);

            // Sudah expired
            if ($exp->isPast()) {
                NotifikasiObat::firstOrCreate([
                    'obat_id' => $obat->id,
                    'pesan'   => "Obat {$obat->nama_obat} sudah expired!"
                ]);
            }

            // Hampir expired (<= 7 hari)
            if ($exp->isFuture() && $exp->diffInDays(Carbon::now()) <= 7) {
                NotifikasiObat::firstOrCreate([
                    'obat_id' => $obat->id,
                    'pesan'   => "Obat {$obat->nama_obat} hampir expired!"
                ]);
            }
        }

        // Kirim notif yang belum dibaca
        $notifikasi = NotifikasiObat::where('dibaca', false)->get();

        View::share('notifikasi', $notifikasi);
        View::share('jumlah_notifikasi', $notifikasi->count());
    }
}
