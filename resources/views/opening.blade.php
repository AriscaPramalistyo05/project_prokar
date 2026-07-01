@php
/**
 * Opening Animation Component
 * Gunakan: @include('opening') di dalam layout/page yang perlu animasi.
 *
 * ponytail: overlay self-contained, config dari tabel settings via helper setting().
 */

$openingEnabled = (bool) (setting('opening_enabled') ?? true);

$defaults = [
    'text_color'    => '#FFFFFF',
    'bg_color'      => '#000000',
    'accent_color'  => '#FECB00',
    'fade_duration' => 900,
    'show_once'     => true,
    'messages'      => [
        ['type' => 'line', 'text' => 'Elektronik rusak bukan akhir dari masa pakainya.', 'duration' => 1900],
        ['type' => 'line', 'text' => 'Perbaiki. Jual. Beli. Dalam satu platform.', 'duration' => 1900],
        ['type' => 'logo', 'text' => 'PROKAR ELEKTRONIK', 'duration' => 2400],
    ],
];

$openingConfig = json_decode(setting('opening_config') ?? '{}', true);
$openingConfig = array_merge($defaults, $openingConfig ?? []);
$openingConfigJson = json_encode($openingConfig, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
@endphp

@if($openingEnabled)
    {{-- Config JSON untuk dibaca oleh JS --}}
    <script id="opening-config" type="application/json">
        {!! $openingConfigJson !!}
    </script>

    {{-- Overlay Opening Animation --}}
    <div
        id="opening-overlay"
        class="opening-overlay"
        style="background-color: {{ $openingConfig['bg_color'] }};"
        role="banner"
        aria-label="Opening Animation"
    >
        {{-- Hazard stripe atas --}}
        <div class="hazard-stripe h-2.5 w-full shrink-0" aria-hidden="true"></div>

        {{-- Frame brutalist --}}
        <div class="relative flex-grow flex items-center justify-center">
            {{-- Brackets --}}
            <span class="opening-bracket top-6 left-6 border-t-4 border-l-4" aria-hidden="true"></span>
            <span class="opening-bracket top-6 right-6 border-t-4 border-r-4" aria-hidden="true"></span>
            <span class="opening-bracket bottom-6 left-6 border-b-4 border-l-4" aria-hidden="true"></span>
            <span class="opening-bracket bottom-6 right-6 border-b-4 border-r-4" aria-hidden="true"></span>

            {{-- Messages container --}}
            <div id="opening-messages" class="relative w-full max-w-2xl h-64" aria-live="polite" aria-atomic="true"></div>
        </div>

        {{-- Hazard stripe bawah --}}
        <div class="hazard-stripe h-2.5 w-full shrink-0" aria-hidden="true"></div>
    </div>

    {{-- Load JS via Vite --}}
    @vite(['resources/js/opening.js'])
@endif
