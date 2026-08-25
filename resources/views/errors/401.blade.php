@extends('errors.layout')

@section('title', '401 — Anda Belum Masuk')
@section('code', '401')
@section('badge_dot', 'bg-blue-600')
@section('image', asset('images/errors/unauthorized.png'))
@section('heading', 'Anda Belum Masuk')
@section('message', 'Masuk ke akun Prokar Elektronik Anda terlebih dahulu untuk mengakses fitur atau halaman ini.')

@section('actions')
    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-slate-900 hover:bg-black text-white text-sm font-bold shadow-sm active:scale-95 transition-all">
        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
        <span>Masuk ke Akun</span>
    </a>
    <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-white hover:bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 active:scale-95 transition-all">
        <i class="fa-solid fa-house text-xs text-slate-400"></i>
        <span>Kembali ke Beranda</span>
    </a>
@endsection
