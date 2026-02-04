<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function bacaSemua()
{
    $user = Auth::user();

    session(['notifikasi_dibaca_' . $user->id => true]);

    return back()->with('success', 'Semua notifikasi sudah dibaca.');
}
}
