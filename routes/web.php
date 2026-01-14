<?php

use App\Livewire\Auth\Login;
use App\Livewire\Counter;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('main.login');
})->name('login');

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('main.dashboard');
    });
    Route::get('/santri-baru', function () {
        return view('main.santri_baru');
    });
    Route::get('/santri-lama', function () {
        return view('main.santri_lama');
    });
    Route::get('/verifikasi-pendaftaran', function () {
        return view('main.verifikasi_pendaftaran');
    });
    Route::get('/pendaftaran-baru', function () {
        return view('main.pendaftaran_baru');
    });
    Route::get('/registrasi-baru', function () {
        return view('main.registrasi_baru');
    });

    Route::get('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
