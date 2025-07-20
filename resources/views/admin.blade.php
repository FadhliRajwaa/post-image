@extends('layouts.app')

@section('title', 'Admin Panel - Image Poster Generator')

@section('content')
    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <h2 class="text-xl font-bold text-white">Admin Panel</h2>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Form Tambah Poster -->
                <div class="md:col-span-1">
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="h-8 w-8 rounded-md bg-indigo-100 flex items-center justify-center mr-2 text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Tambah Poster Baru</h3>
                        </div>
                        
                        <form action="{{ route('posters.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700">Judul</label>
                                <input type="text" name="judul" id="judul" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150" 
                                    maxlength="50" required value="{{ old('judul') }}" placeholder="Masukkan judul poster">
                                @error('judul')
                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="narasi" class="block text-sm font-medium text-gray-700">Narasi</label>
                                <textarea name="narasi" id="narasi" rows="4" 
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150"
                                    maxlength="1000" required placeholder="Masukkan narasi poster">{{ old('narasi') }}</textarea>
                                <div class="flex justify-between items-center mt-1.5">
                                    <p class="text-xs text-gray-500">Caption untuk poster</p>
                                    <p class="text-xs text-gray-500 font-medium">Maks. 1000 karakter</p>
                                </div>
                                @error('narasi')
                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar Utama</label>
                                <input type="file" name="gambar" id="gambar" 
                                    class="mt-1.5 block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition duration-150 file:shadow-sm"
                                    accept="image/png,image/jpeg,image/jpg" required>
                                <p class="text-xs text-gray-500 mt-1.5">Format: JPG, JPEG, PNG (max 5MB)</p>
                                @error('gambar')
                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="frame" class="block text-sm font-medium text-gray-700">Frame (Overlay)</label>
                                <div class="bg-indigo-50 p-3 rounded-lg mb-2 text-xs text-indigo-700">
                                    <div class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p class="font-semibold mb-1">Panduan Frame:</p>
                                            <ul class="list-disc ml-4 space-y-1">
                                                <li>Gunakan file PNG dengan transparansi</li>
                                                <li>Frame akan ditampilkan sebagai lapisan di atas gambar utama</li>
                                                <li>Resolusi ideal: 2000×2000px</li>
                                                <li>Area transparan akan menampilkan gambar utama di bawahnya</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" name="frame" id="frame" 
                                    class="mt-1.5 block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition duration-150 file:shadow-sm"
                                    accept="image/png" required>
                                <p class="text-xs text-gray-500 mt-1.5">Format: PNG dengan transparansi (max 5MB)</p>
                                @error('frame')
                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pengaturan Gambar -->
                            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100 shadow-sm">
                                <h4 class="text-sm font-medium text-indigo-700 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Sesuaikan Gambar
                                </h4>
                                
                                <!-- Kontrol Skala -->
                                <div class="mb-3">
                                    <label for="scale_gambar" class="block text-xs font-medium text-indigo-800 mb-1">Skala Gambar: <span id="scale-value">1.00</span>x</label>
                                    <div class="flex items-center gap-2">
                                        <button type="button" id="scale-down" class="p-1 rounded-md bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <input type="range" id="scale_gambar_range" name="scale_gambar" min="0.1" max="2" step="0.05" value="1" 
                                            class="flex-1 h-1.5 bg-indigo-200 rounded-lg appearance-none cursor-pointer">
                                        <button type="button" id="scale-up" class="p-1 rounded-md bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Posisi Gambar -->
                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label for="pos_x" class="block text-xs font-medium text-indigo-800 mb-1">Geser Horizontal: <span id="pos-x-value">0</span>px</label>
                                        <div class="flex items-center gap-2">
                                            <button type="button" id="pos-x-left" class="p-1.5 rounded-md bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" title="Geser ke kiri">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                </svg>
                                            </button>
                                            <input type="range" id="pos_x_range" name="pos_x" min="-500" max="500" step="10" value="0" 
                                                class="flex-1 h-1.5 bg-indigo-200 rounded-lg appearance-none cursor-pointer">
                                            <button type="button" id="pos-x-right" class="p-1.5 rounded-md bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" title="Geser ke kanan">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="pos_y" class="block text-xs font-medium text-indigo-800 mb-1">Geser Vertikal: <span id="pos-y-value">0</span>px</label>
                                        <div class="flex items-center gap-2">
                                            <button type="button" id="pos-y-up" class="p-1.5 rounded-md bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" title="Geser ke atas">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                </svg>
                                            </button>
                                            <input type="range" id="pos_y_range" name="pos_y" min="-500" max="500" step="10" value="0" 
                                                class="flex-1 h-1.5 bg-indigo-200 rounded-lg appearance-none cursor-pointer">
                                            <button type="button" id="pos-y-down" class="p-1.5 rounded-md bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" title="Geser ke bawah">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="button" id="reset-position" class="mt-2 text-xs font-medium text-indigo-700 hover:text-indigo-900 inline-flex items-center focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset Posisi
                                </button>
                            </div>
                            
                            <div>
                                <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah Poster Baru
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Live Preview -->
                    <div id="preview-container" class="mt-6 bg-gradient-to-br from-indigo-50 to-purple-50 p-6 rounded-xl shadow-md border border-indigo-100 hidden">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                            Live Preview
                        </h3>
                        <div class="relative aspect-square rounded-xl overflow-hidden border-4 border-white shadow-lg">
                            <img id="preview-image" class="w-full h-full object-cover absolute inset-0 hidden" src="" alt="Preview Gambar">
                            <img id="preview-frame" class="absolute inset-0 w-full h-full object-contain z-10 hidden" src="" alt="Preview Frame">
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 z-20">
                                <h4 id="preview-judul" class="text-white text-xl font-bold mb-1 drop-shadow-lg text-center">Judul Poster</h4>
                                <p id="preview-narasi" class="text-white text-sm drop-shadow-lg text-center">Narasi poster akan ditampilkan di sini</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-3">
                            <p class="text-xs text-gray-500">*Ini hanya preview. Hasil akhir 2000×2000px</p>
                            <span class="text-xs text-indigo-600 font-medium">Frame sebagai overlay</span>
                        </div>
                    </div>
                </div>
                
                <!-- Daftar Poster -->
                <div class="md:col-span-2">
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-md bg-indigo-100 flex items-center justify-center mr-2 text-indigo-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Daftar Poster</h3>
                            </div>
                            
                            @if(count($posters) > 0)
                            <form action="{{ route('posters.destroyAll') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus semua poster? Tindakan ini tidak bisa dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Semua Poster
                                </button>
                            </form>
                            @endif
                        </div>
                        
                        @if(count($posters) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                @foreach($posters as $poster)
                                    <div class="bg-white rounded-xl overflow-hidden shadow-md border border-gray-100 transition-all duration-200 hover:shadow-lg hover:border-indigo-200">
                                        <div class="p-2 bg-gray-50">
                                            <div class="aspect-square relative overflow-hidden rounded-lg">
                                                @if($poster->hasil_final)
                                                    <img src="{{ asset('storage/' . $poster->hasil_final) }}" alt="{{ $poster->judul }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full relative bg-gray-50">
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
                                            </div>
                                        </div>
                                        
                                        <div class="p-4">
                                            <h4 class="font-semibold text-lg mb-1 truncate">{{ $poster->judul }}</h4>
                                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $poster->narasi }}</p>
                                            
                                            <div class="grid grid-cols-2 gap-2 mb-4">
                                                <button 
                                                    class="copy-narasi-btn w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1 transition duration-150"
                                                    data-narasi="{{ $poster->narasi }}"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                    Copy Narasi
                                                </button>
                                                
                                                @if($poster->hasil_final)
                                                    <a 
                                                        href="{{ asset('storage/' . $poster->hasil_final) }}"
                                                        download="{{ $poster->judul }}.jpg"
                                                        class="download-image-btn w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded-lg text-xs font-medium flex items-center justify-center gap-1 transition duration-150"
                                                        data-image="{{ asset('storage/' . $poster->hasil_final) }}"
                                                        data-filename="{{ $poster->judul }}"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                        Download
                                                    </a>
                                                @endif
                                            </div>
                                            
                                            <div class="flex justify-between">
                                                <a href="{{ route('posters.show', $poster->id) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500 transition duration-150">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Detail
                                                </a>
                                                
                                                <form action="{{ route('posters.destroy', $poster->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus poster ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-500 transition duration-150">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 p-8 rounded-lg text-center border border-gray-100">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-medium text-gray-900 mb-1">Belum Ada Poster</h4>
                                    <p class="text-gray-600 mb-4">Tambahkan poster pertama Anda menggunakan formulir di sebelah kiri.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 