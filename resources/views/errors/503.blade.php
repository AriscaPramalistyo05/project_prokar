@extends('errors.layout')

@section('title', '503 — Sedang Pemeliharaan')
@section('code', '503')
@section('badge_dot', 'bg-amber-500')
@section('image', asset('images/errors/maintenance.png'))
@section('heading', 'Layanan Sedang Pemeliharaan')
@section('message', 'Sistem Prokar Elektronik sedang dalam proses peningkatan dan pemeliharaan server berkala. Kami akan segera kembali online.')

@section('actions')
    <button type="button" onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-slate-900 hover:bg-black text-white text-sm font-bold shadow-sm active:scale-95 transition-all cursor-pointer">
        <i class="fa-solid fa-rotate-right text-xs"></i>
        <span>Muat Ulang Halaman</span>
    </button>
    <a href="https://wa.me/6289504841279?text=Halo%20Admin%20Prokar,%20apakah%20layanan%20sudah%20selesai%20pemeliharaan?" target="_blank" rel="noopener" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm active:scale-95 transition-all">
        <i class="fa-brands fa-whatsapp text-sm"></i>
        <span>Cek Status via WhatsApp</span>
    </a>
@endsection
