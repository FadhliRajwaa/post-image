@extends('layouts.app')

@section('title', 'Image Poster Generator')

@section('content')
    <div class="relative bg-gradient-to-br from-purple-900 via-indigo-800 to-blue-900 py-16 mb-8 rounded-lg overflow-hidden">
        <div class="max-w-4xl mx-auto text-center text-white px-4 relative z-10">
            <h1 class="text-4xl font-extrabold mb-3 drop-shadow-md">Image Poster Gallery</h1>
            <p class="text-lg text-purple-100">Koleksi poster gambar dengan desain modern dan siap diunduh</p>
        </div>
        <!-- Dekoratif shapes -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-20 left-10 w-24 h-24 rounded-full bg-purple-400 mix-blend-multiply"></div>
            <div class="absolute bottom-10 right-10 w-32 h-32 rounded-full bg-blue-400 mix-blend-multiply"></div>
            <div class="absolute top-40 right-1/4 w-20 h-20 rounded-full bg-indigo-400 mix-blend-multiply"></div>
        </div>
    </div>
    
    @if(count($posters) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posters as $poster)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="relative">
                        @if($poster->hasil_final)
                            <img src="{{ asset('storage/' . $poster->hasil_final) }}" alt="{{ $poster->judul }}" class="w-full aspect-square object-cover">
                        @else
                            <div class="w-full aspect-square relative bg-gray-50">
                                <!-- Gambar utama sebagai background -->
                                <img src="{{ asset('storage/' . $poster->gambar) }}" alt="{{ $poster->judul }}" class="w-full h-full object-cover absolute inset-0">
                                
                                <!-- Frame sebagai overlay -->
                                <img src="{{ asset('storage/' . $poster->frame) }}" alt="Frame" class="absolute inset-0 w-full h-full object-contain z-10">
                                
                                <!-- Teks overlay -->
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <!-- Menggunakan font Arial Black untuk judul dan Calibri Bold untuk narasi -->
                                    <h4 class="text-center text-white text-xl mb-1 drop-shadow-lg" style="font-family: 'Arial Black', 'Arial Bold', sans-serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">{{ $poster->judul }}</h4>
                                    <p class="text-center text-white text-sm drop-shadow-lg" style="font-family: 'Calibri Bold', 'Calibri', sans-serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">{{ Str::limit($poster->narasi, 50) }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6 z-30">
                            <h3 class="text-white text-xl font-bold mb-1 drop-shadow-lg">{{ $poster->judul }}</h3>
                            <p class="text-white text-sm mb-4 line-clamp-2 drop-shadow-lg">{{ $poster->narasi }}</p>
                        </div>
                    </div>
                    
                    <div class="p-5 bg-white border-t">
                        <div class="flex space-x-3">
                            <button 
                                class="copy-narasi-btn flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 px-4 rounded-lg text-sm font-medium flex items-center justify-center gap-2 shadow-sm"
                                data-narasi="{{ $poster->narasi }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Copy Narasi
                            </button>
                            
                            <a 
                                href="{{ route('download.page', $poster->id) }}"
                                class="download-btn flex-1 bg-green-600 hover:bg-green-700 text-white py-2.5 px-4 rounded-lg text-sm font-medium flex items-center justify-center gap-2 shadow-sm"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-24 h-24 rounded-full bg-indigo-100 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Poster</h3>
                <p class="text-gray-600 mb-6">Belum ada poster yang ditambahkan ke galeri</p>
                
                <a href="{{ route('admin') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Buat Poster Baru
                </a>
            </div>
        </div>
    @endif
@endsection 