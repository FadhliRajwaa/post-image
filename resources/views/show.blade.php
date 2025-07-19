@extends('layouts.app')

@section('title', $poster->judul . ' - Image Poster Generator')

@section('content')
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Detail Poster</h2>
            <a href="{{ route('admin') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                &larr; Kembali ke Admin
            </a>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Preview Poster -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="aspect-square relative overflow-hidden rounded-lg shadow-sm">
                        @if($poster->hasil_final)
                            <img src="{{ asset('storage/' . $poster->hasil_final) }}" alt="{{ $poster->judul }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full relative">
                                <!-- Gambar utama sebagai background -->
                                <img src="{{ asset('storage/' . $poster->gambar) }}" alt="{{ $poster->judul }}" class="w-full h-full object-cover absolute inset-0">
                                
                                <!-- Frame sebagai overlay -->
                                <img src="{{ asset('storage/' . $poster->frame) }}" alt="Frame" class="w-full h-full object-contain absolute inset-0 z-10">
                                
                                <!-- Teks overlay -->
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 z-20">
                                    <h4 class="text-white text-lg font-bold mb-1 drop-shadow-lg">{{ $poster->judul }}</h4>
                                    <p class="text-white text-sm drop-shadow-lg">{{ Str::limit($poster->narasi, 100) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4 flex space-x-2">
                        <button 
                            class="copy-narasi-btn flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md text-sm font-medium flex items-center justify-center gap-1"
                            data-narasi="{{ $poster->narasi }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Copy Narasi
                        </button>
                        
                        @if($poster->hasil_final)
                            <a 
                                href="{{ asset('storage/' . $poster->hasil_final) }}"
                                download="{{ $poster->judul }}"
                                class="download-image-btn flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md text-sm font-medium flex items-center justify-center gap-1"
                                data-image="{{ asset('storage/' . $poster->hasil_final) }}"
                                data-filename="{{ $poster->judul }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Detail Poster -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $poster->judul }}</h3>
                    <div class="mb-6">
                        <p class="text-gray-700 whitespace-pre-line">{{ $poster->narasi }}</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Informasi File</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Gambar Utama:</span>
                                <span class="text-gray-900 font-medium">{{ basename($poster->gambar) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Frame:</span>
                                <span class="text-gray-900 font-medium">{{ basename($poster->frame) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Hasil Final:</span>
                                <span class="text-gray-900 font-medium">
                                    @if($poster->hasil_final)
                                        {{ basename($poster->hasil_final) }}
                                    @else
                                        <span class="text-yellow-600">Belum diproses</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dibuat pada:</span>
                                <span class="text-gray-900 font-medium">{{ $poster->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('posters.edit', $poster->id) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Edit Poster
                        </a>
                        
                        <form action="{{ route('posters.destroy', $poster->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus poster ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Hapus Poster
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 