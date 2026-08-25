@extends('errors.layout')

@section('title', '404 — Halaman Tidak Ditemukan')
@section('code', '404')
@section('badge_dot', 'bg-amber-500')
@section('image', asset('images/errors/not_found.png'))
@section('heading', 'Halaman Tidak Ditemukan')
@section('message', 'Maaf, halaman atau produk yang Anda tuju mungkin telah dihapus, dipindahkan, atau alamat URL yang dimasukkan salah.')

@section('actions')
    <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-slate-900 hover:bg-black text-white text-sm font-bold shadow-sm active:scale-95 transition-all">
        <i class="fa-solid fa-house text-xs"></i>
        <span>Kembali ke Beranda</span>
    </a>
    <a href="{{ route('produk.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-[#FFCC00] hover:bg-amber-400 text-black text-sm font-bold shadow-sm active:scale-95 transition-all">
        <i class="fa-solid fa-box text-xs"></i>
        <span>Cari Produk Elektronik</span>
    </a>
@endsection
