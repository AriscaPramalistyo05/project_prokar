@extends('errors.layout')

@section('title', '403 — Akses Ditolak')
@section('code', '403')
@section('badge_dot', 'bg-rose-600')
@section('image', asset('images/errors/forbidden.png'))
@section('heading', 'Akses Ditolak')
@section('message', $exception?->getMessage() ?: 'Anda tidak memiliki hak akses atau izin yang cukup untuk melihat halaman atau mengunduh dokumen ini.')

@section('actions')
    @auth
        @if(auth()->user()->hasAnyRole(['super_admin', 'teknisi', 'admin']))
            <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-slate-900 hover:bg-black text-white text-sm font-bold shadow-sm active:scale-95 transition-all">
                <i class="fa-solid fa-gauge-high text-xs"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        @else
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-slate-900 hover:bg-black text-white text-sm font-bold shadow-sm active:scale-95 transition-all">
                <i class="fa-solid fa-house text-xs"></i>
                <span>Kembali ke Beranda</span>
            </a>
        @endif
    @else
        <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-slate-900 hover:bg-black text-white text-sm font-bold shadow-sm active:scale-95 transition-all">
            <i class="fa-solid fa-house text-xs"></i>
            <span>Kembali ke Beranda</span>
        </a>
    @endauth

    <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ url('/') }}'" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-white hover:bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 active:scale-95 transition-all cursor-pointer">
        <i class="fa-solid fa-arrow-left text-xs text-slate-400"></i>
        <span>Halaman Sebelumnya</span>
    </button>
@endsection
