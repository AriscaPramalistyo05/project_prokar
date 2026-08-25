@extends('errors.layout')

@section('title', '500 — Kesalahan Server')
@section('code', '500')
@section('badge_dot', 'bg-rose-600')
@section('image', asset('images/errors/internal_server_error.png'))
@section('heading', 'Kesalahan Server Internal')
@section('message', 'Sistem kami mengalami kendala teknis tak terduga. Tim teknisi kami telah menerima log error ini dan sedang menanganinya.')

@section('actions')
    <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-slate-900 hover:bg-black text-white text-sm font-bold shadow-sm active:scale-95 transition-all">
        <i class="fa-solid fa-house text-xs"></i>
        <span>Kembali ke Beranda</span>
    </a>
    <a href="https://wa.me/6289504841279?text=Halo%20Admin%20Prokar,%20saya%20mengalami%20kendala%20error%20500%20saat%20mengakses%20website" target="_blank" rel="noopener" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm active:scale-95 transition-all">
        <i class="fa-brands fa-whatsapp text-sm"></i>
        <span>Hubungi CS WhatsApp</span>
    </a>
@endsection
