@props(['active'])

@php
$classes = ($active ?? false)
            // JIKA AKTIF: Background Putih, Teks Biru Gelap (Kita paksa dengan !text-blue-900)
            ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-blue-600 text-left text-base font-bold text-blue-900 bg-white focus:outline-none transition duration-150 ease-in-out'
            
            // JIKA TIDAK AKTIF: Teks Putih (text-white), Hover sedikit gelap
            : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-white hover:text-blue-100 hover:bg-blue-800 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>