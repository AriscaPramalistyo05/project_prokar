@extends('errors.layout')

@section('title', '419 — Sesi Berakhir')
@section('code', '419')
@section('badge_dot', 'bg-amber-600')
@section('image', asset('images/errors/page_expired.png'))
@section('heading', 'Sesi Telah Berakhir')
@section('message', 'Sesi keamanan form Anda telah kedaluwarsa demi melindungi privasi dan data akun Anda. Silakan muat ulang halaman.')

@section('actions')
    <button type="button" onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-slate-900 hover:bg-black text-white text-sm font-bold shadow-sm active:scale-95 transition-all cursor-pointer">
        <i class="fa-solid fa-rotate-right text-xs"></i>
        <span>Muat Ulang Halaman</span>
    </button>
    <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-white hover:bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 active:scale-95 transition-all">
        <i class="fa-solid fa-house text-xs text-slate-400"></i>
        <span>Kembali ke Beranda</span>
    </a>
@endsection
