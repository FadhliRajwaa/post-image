@extends('layouts.app')

@section('title', 'Download Poster - ' . $poster->judul)

@section('content')
<div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-4xl mx-auto">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            <h2 class="text-xl font-bold text-white">Download Poster</h2>
        </div>
    </div>
    
    <!-- Alert Messages -->
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 mx-6 mt-4">
        <p>{{ session('error') }}</p>
    </div>
    @endif
    
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 mx-6 mt-4">
        <p>{{ session('success') }}</p>
    </div>
    @endif
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Preview Poster -->
            <div class="bg-gray-50 p-4 rounded-xl">
                <div class="aspect-square relative overflow-hidden rounded-lg shadow-sm">
                    @if($poster->hasil_final)
                        <img src="{{ asset('storage/' . $poster->hasil_final) }}" alt="{{ $poster->judul }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full relative bg-gray-50">
                            <!-- Gambar utama sebagai background -->
                            <img src="{{ asset('storage/' . $poster->gambar) }}" alt="{{ $poster->judul }}" class="w-full h-full object-cover absolute inset-0">
                            
                            <!-- Frame sebagai overlay -->
                            <img src="{{ asset('storage/' . $poster->frame) }}" alt="Frame" class="absolute inset-0 w-full h-full object-contain z-10">
                            
                            <!-- Teks overlay -->
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 z-20">
                                <h4 class="text-white text-xl font-bold mb-1 drop-shadow-lg text-center">{{ $poster->judul }}</h4>
                                <p class="text-white text-sm drop-shadow-lg text-center">{{ Str::limit($poster->narasi, 100) }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Download Info -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $poster->judul }}</h3>
                <div class="mb-6">
                    <p class="text-gray-700 whitespace-pre-line">{{ $poster->narasi }}</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-5 mb-6 border border-gray-100">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Poster</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                            Format: JPG
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            Resolusi: 2000 × 2000 pixel
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Kualitas tinggi (95%)
                        </li>
                    </ul>
                </div>
                
                <div class="space-y-4">
                    <a 
                        href="{{ route('download.image', $poster->id) }}"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Poster (2000×2000px)
                    </a>
                    
                    <!-- Tombol Regenerasi Poster -->
                    <form action="{{ route('posters.regenerate', $poster->id) }}" method="POST">
                        @csrf
                        <button 
                            type="submit"
                            class="w-full flex justify-center items-center py-3 px-4 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Regenerasi Poster
                        </button>
                    </form>
                    
                    <button 
                        class="copy-narasi-btn w-full flex justify-center items-center py-3 px-4 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150"
                        data-narasi="{{ $poster->narasi }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Salin Narasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 