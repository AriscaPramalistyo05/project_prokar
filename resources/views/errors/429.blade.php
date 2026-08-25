@extends('errors.layout')

@section('title', '429 — Terlalu Banyak Permintaan')
@section('code', '429')
@section('badge_dot', 'bg-purple-600')
@section('image', asset('images/errors/too_many_requests.png'))
@section('heading', 'Terlalu Banyak Permintaan')
@section('message', 'Anda mengirim terlalu banyak permintaan dalam waktu yang sangat singkat. Mohon tunggu beberapa saat lalu coba lagi.')

@section('actions')
    <button type="button" onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-slate-900 hover:bg-black text-white text-sm font-bold shadow-sm active:scale-95 transition-all cursor-pointer">
        <i class="fa-solid fa-arrows-rotate text-xs"></i>
        <span>Coba Lagi Sekarang</span>
    </button>
    <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-white hover:bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 active:scale-95 transition-all">
        <i class="fa-solid fa-house text-xs text-slate-400"></i>
        <span>Kembali ke Beranda</span>
    </a>
@endsection
