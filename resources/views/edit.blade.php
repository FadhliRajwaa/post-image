@extends('layouts.app')

@section('title', 'Edit Poster - ' . $poster->judul)

@section('content')
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Edit Poster</h2>
            <a href="{{ route('admin') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                &larr; Kembali ke Admin
            </a>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Form Edit Poster -->
                <div class="md:col-span-2">
                    <form action="{{ route('posters.update', $poster->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700">Judul</label>
                            <input type="text" name="judul" id="judul" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                maxlength="50" required value="{{ old('judul', $poster->judul) }}">
                            @error('judul')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="narasi" class="block text-sm font-medium text-gray-700">Narasi</label>
                            <textarea name="narasi" id="narasi" rows="5" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                maxlength="1000" required>{{ old('narasi', $poster->narasi) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Maksimal 1000 karakter</p>
                            @error('narasi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar Utama</label>
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $poster->gambar) }}" alt="{{ $poster->judul }}" class="w-full h-40 object-cover rounded-lg mb-2" id="current-gambar">
                                </div>
                                <input type="file" name="gambar" id="gambar" 
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                    accept="image/png,image/jpeg,image/jpg">
                                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar</p>
                                @error('gambar')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="frame" class="block text-sm font-medium text-gray-700">Frame (Overlay)</label>
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $poster->frame) }}" alt="Frame" class="w-full h-40 object-cover rounded-lg mb-2" id="current-frame">
                                </div>
                                <input type="file" name="frame" id="frame" 
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                    accept="image/png">
                                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah frame</p>
                                @error('frame')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Pengaturan Gambar -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                            <h3 class="font-medium text-gray-700 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Sesuaikan Gambar
                            </h3>
                            <div class="space-y-4">
                                <!-- Kontrol Skala -->
                                <div>
                                    <label for="scale_gambar" class="block text-sm font-medium text-gray-600 mb-1">Skala Gambar: <span id="scale-value">{{ old('scale_gambar', $poster->scale_gambar ?? 1.0) }}</span>x</label>
                                    <div class="flex items-center gap-2">
                                        <button type="button" id="scale-down" class="p-1.5 rounded-md bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <input type="range" id="scale_gambar_range" name="scale_gambar" min="0.1" max="2" step="0.05" value="{{ old('scale_gambar', $poster->scale_gambar ?? 1.0) }}" 
                                            class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                        <button type="button" id="scale-up" class="p-1.5 rounded-md bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Posisi Gambar -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Posisi Gambar</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="pos_x" class="block text-sm font-medium text-gray-600 mb-1">Geser Horizontal: <span id="pos-x-value">{{ old('pos_x', $poster->pos_x ?? 0) }}</span>px</label>
                                            <div class="flex items-center gap-2">
                                                <button type="button" id="pos-x-left" class="p-1.5 rounded-md bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Geser ke kiri">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                                    </svg>
                                                </button>
                                                <input type="range" id="pos_x_range" name="pos_x" min="-500" max="500" step="10" value="{{ old('pos_x', $poster->pos_x ?? 0) }}" 
                                                    class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                                <button type="button" id="pos-x-right" class="p-1.5 rounded-md bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Geser ke kanan">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="pos_y" class="block text-sm font-medium text-gray-600 mb-1">Geser Vertikal: <span id="pos-y-value">{{ old('pos_y', $poster->pos_y ?? 0) }}</span>px</label>
                                            <div class="flex items-center gap-2">
                                                <button type="button" id="pos-y-up" class="p-1.5 rounded-md bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Geser ke atas">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                    </svg>
                                                </button>
                                                <input type="range" id="pos_y_range" name="pos_y" min="-500" max="500" step="10" value="{{ old('pos_y', $poster->pos_y ?? 0) }}" 
                                                    class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                                <button type="button" id="pos-y-down" class="p-1.5 rounded-md bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Geser ke bawah">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-center">
                                        <button type="button" id="reset-position" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Reset ke Posisi Default
                                        </button>
                                    </div>
                                </div>

                                <!-- Pengaturan Jarak Teks -->
                                <div class="mt-5 border-t border-gray-100 pt-5">
                                    <label class="block text-sm font-medium text-gray-600 mb-2">Jarak Antar Teks</label>
                                    
                                    <div>
                                        <label for="judul_narasi_gap" class="block text-xs text-gray-500 mb-1">Jarak Judul & Narasi: <span id="gap-value">{{ old('judul_narasi_gap', $poster->judul_narasi_gap ?? 300) }}</span>px</label>
                                        <div class="flex items-center gap-2">
                                            <button type="button" id="gap-decrease" class="p-1.5 rounded-md bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Kurangi jarak">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            </button>
                                            <input type="range" id="judul_narasi_gap" name="judul_narasi_gap" min="50" max="500" step="5" value="{{ old('judul_narasi_gap', $poster->judul_narasi_gap ?? 300) }}" 
                                                class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                            <button type="button" id="gap-increase" class="p-1.5 rounded-md bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Tambah jarak">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-400 italic">Mengatur jarak antara judul dan narasi pada poster</p>
                                    </div>
                                    
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <label class="block text-sm font-medium text-gray-600 mb-2">Posisi Teks</label>
                                        
                                        <div class="mb-3">
                                            <label for="judul_y" class="block text-xs text-gray-500 mb-1">Posisi Judul (Y): <span id="judul-y-value">{{ old('judul_y', $poster->judul_y ?? 1600) }}</span>px</label>
                                            <div class="flex items-center gap-2">
                                                <button type="button" id="judul-y-up" class="p-1.5 rounded-md bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Geser ke atas">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                    </svg>
                                                </button>
                                                <input type="range" id="judul_y_range" name="judul_y" min="1000" max="1800" step="10" value="{{ old('judul_y', $poster->judul_y ?? 1600) }}" 
                                                    class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                                <button type="button" id="judul-y-down" class="p-1.5 rounded-md bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Geser ke bawah">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-400 italic">Mengatur posisi vertikal judul dari atas</p>
                                        </div>
                                        
                                        <div>
                                            <label for="narasi_y" class="block text-xs text-gray-500 mb-1">Posisi Narasi (Y): <span id="narasi-y-value">{{ old('narasi_y', $poster->narasi_y ?? 1900) }}</span>px</label>
                                            <div class="flex items-center gap-2">
                                                <button type="button" id="narasi-y-up" class="p-1.5 rounded-md bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Geser ke atas">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                    </svg>
                                                </button>
                                                <input type="range" id="narasi_y_range" name="narasi_y" min="1400" max="1950" step="10" value="{{ old('narasi_y', $poster->narasi_y ?? 1900) }}" 
                                                    class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                                <button type="button" id="narasi-y-down" class="p-1.5 rounded-md bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Geser ke bawah">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-400 italic">Mengatur posisi vertikal narasi dari atas</p>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Preview Poster -->
                <div class="md:col-span-1">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Live Preview</h3>
                        <div class="aspect-square relative overflow-hidden rounded-lg shadow-sm" id="preview-container">
                            <!-- Gambar utama sebagai background -->
                            <img src="{{ asset('storage/' . $poster->gambar) }}" alt="{{ $poster->judul }}" 
                                class="w-full h-full object-cover absolute inset-0" id="preview-image">
                            
                            <!-- Frame sebagai overlay -->
                            <img src="{{ asset('storage/' . $poster->frame) }}" alt="Frame" 
                                class="absolute inset-0 w-full h-full object-cover" id="preview-frame">
                            
                            <!-- Teks overlay -->
                            <div class="absolute inset-0 w-full h-full">
                                <!-- Menggunakan font Arial Black untuk judul dan Calibri Bold untuk narasi -->
                                <div id="preview-judul-container" class="absolute w-full px-4" style="top: 65%;">
                                    <h4 id="preview-judul" class="text-center text-white text-2xl drop-shadow-lg" style="font-family: 'Arial Black', 'Arial Bold', sans-serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">{{ $poster->judul }}</h4>
                                </div>
                                <div id="preview-narasi-container" class="absolute w-full px-4" style="top: 75%;">
                                    <p id="preview-narasi" class="text-center text-white text-base drop-shadow-lg" style="font-family: 'Calibri Bold', 'Calibri', sans-serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">{{ Str::limit($poster->narasi, 100) }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-center">Preview akan diperbarui secara otomatis saat Anda mengedit.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elemen input
            const judulInput = document.getElementById('judul');
            const narasiInput = document.getElementById('narasi');
            const gambarInput = document.getElementById('gambar');
            const frameInput = document.getElementById('frame');
            
            // Elemen preview
            const previewJudul = document.getElementById('preview-judul');
            const previewNarasi = document.getElementById('preview-narasi');
            const previewImage = document.getElementById('preview-image');
            const previewFrame = document.getElementById('preview-frame');
            const currentGambar = document.getElementById('current-gambar');
            const currentFrame = document.getElementById('current-frame');
            
            // Elemen preview containers
            const judulContainer = document.getElementById('preview-judul-container');
            const narasiContainer = document.getElementById('preview-narasi-container');
            
            // Elemen pengaturan gambar
            const scaleRangeInput = document.getElementById('scale_gambar_range');
            const scaleValueElement = document.getElementById('scale-value');
            const scaleDownButton = document.getElementById('scale-down');
            const scaleUpButton = document.getElementById('scale-up');
            
            const posXRangeInput = document.getElementById('pos_x_range');
            const posYRangeInput = document.getElementById('pos_y_range');
            const posXValueElement = document.getElementById('pos-x-value');
            const posYValueElement = document.getElementById('pos-y-value');
            const posXLeftButton = document.getElementById('pos-x-left');
            const posXRightButton = document.getElementById('pos-x-right');
            const posYUpButton = document.getElementById('pos-y-up');
            const posYDownButton = document.getElementById('pos-y-down');
            const resetPositionButton = document.getElementById('reset-position');
            
            // Elemen kontrol posisi Y judul dan narasi
            const judulYRangeInput = document.getElementById('judul_y_range');
            const narasiYRangeInput = document.getElementById('narasi_y_range');
            const judulYValueElement = document.getElementById('judul-y-value');
            const narasiYValueElement = document.getElementById('narasi-y-value');
            const judulYUpButton = document.getElementById('judul-y-up');
            const judulYDownButton = document.getElementById('judul-y-down');
            const narasiYUpButton = document.getElementById('narasi-y-up');
            const narasiYDownButton = document.getElementById('narasi-y-down');
            
            // Elemen jarak judul & narasi
            const gapRangeInput = document.getElementById('judul_narasi_gap');
            const gapValueElement = document.getElementById('gap-value');
            const gapDecreaseButton = document.getElementById('gap-decrease');
            const gapIncreaseButton = document.getElementById('gap-increase');
            
            // Fungsi untuk menginisialisasi posisi awal
            function initializePositions() {
                // Inisialisasi posisi Y judul
                if (judulContainer) {
                    const judulY = judulYRangeInput ? parseInt(judulYRangeInput.value) : 1600;
                    const percentage = (judulY - 1000) / 800; // 0-1
                    const topPosition = 40 + percentage * 40; // 40%-80%
                    judulContainer.style.top = topPosition + '%';
                }
                
                // Inisialisasi posisi Y narasi
                if (narasiContainer) {
                    const narasiY = narasiYRangeInput ? parseInt(narasiYRangeInput.value) : 1900;
                    const percentage = (narasiY - 1400) / 550; // 0-1
                    const topPosition = 50 + percentage * 40; // 50%-90%
                    narasiContainer.style.top = topPosition + '%';
                }
            }
            
            // Update judul saat input berubah
            judulInput.addEventListener('input', function() {
                previewJudul.textContent = this.value || '{{ $poster->judul }}';
            });
            
            // Update narasi saat input berubah
            narasiInput.addEventListener('input', function() {
                let text = this.value || '{{ $poster->narasi }}';
                if (text.length > 200) { // Meningkatkan preview dari 100 menjadi 200
                    text = text.substring(0, 200) + '...';
                }
                previewNarasi.textContent = text;
            });
            
            // Update gambar saat file dipilih
            gambarInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        currentGambar.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Update frame saat file dipilih
            frameInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewFrame.src = e.target.result;
                        currentFrame.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Fungsi untuk memperbarui tampilan skala dan posisi gambar
            function updateImageTransform() {
                const scale = parseFloat(scaleRangeInput.value);
                const posX = parseInt(posXRangeInput.value);
                const posY = parseInt(posYRangeInput.value);
                
                previewImage.style.transform = `translate(${posX}px, ${posY}px) scale(${scale})`;
                previewImage.style.transformOrigin = 'center';
                
                // Update nilai yang ditampilkan
                scaleValueElement.textContent = scale.toFixed(2);
                posXValueElement.textContent = posX;
                posYValueElement.textContent = posY;
            }
            
            // Inisialisasi tampilan awal
            updateImageTransform();
            initializePositions();

            // Event listener untuk slider skala
            scaleRangeInput.addEventListener('input', updateImageTransform);
            
            // Event listener untuk slider posisi
            posXRangeInput.addEventListener('input', updateImageTransform);
            posYRangeInput.addEventListener('input', updateImageTransform);
            
            // Event listener untuk tombol kontrol skala
            scaleDownButton.addEventListener('click', function() {
                const currentScale = parseFloat(scaleRangeInput.value);
                const newScale = Math.max(0.1, currentScale - 0.05);
                scaleRangeInput.value = newScale;
                updateImageTransform();
            });
            
            scaleUpButton.addEventListener('click', function() {
                const currentScale = parseFloat(scaleRangeInput.value);
                const newScale = Math.min(2, currentScale + 0.05);
                scaleRangeInput.value = newScale;
                updateImageTransform();
            });
            
            // Event listener untuk tombol kontrol posisi
            posXLeftButton.addEventListener('click', function() {
                const currentPos = parseInt(posXRangeInput.value);
                const newPos = Math.max(-500, currentPos - 10);
                posXRangeInput.value = newPos;
                updateImageTransform();
            });
            
            posXRightButton.addEventListener('click', function() {
                const currentPos = parseInt(posXRangeInput.value);
                const newPos = Math.min(500, currentPos + 10);
                posXRangeInput.value = newPos;
                updateImageTransform();
            });
            
            posYUpButton.addEventListener('click', function() {
                const currentPos = parseInt(posYRangeInput.value);
                const newPos = Math.max(-500, currentPos - 10);
                posYRangeInput.value = newPos;
                updateImageTransform();
            });
            
            posYDownButton.addEventListener('click', function() {
                const currentPos = parseInt(posYRangeInput.value);
                const newPos = Math.min(500, currentPos + 10);
                posYRangeInput.value = newPos;
                updateImageTransform();
            });
            
            // Reset posisi dan skala
            resetPositionButton.addEventListener('click', function() {
                scaleRangeInput.value = 1.0;
                posXRangeInput.value = 0;
                posYRangeInput.value = 0;
                updateImageTransform();
            });

            // Fungsi untuk memperbarui tampilan posisi Y judul
            function updateJudulYPosition() {
                if (!judulYRangeInput || !judulYValueElement) return;
                
                const judulY = parseInt(judulYRangeInput.value);
                judulYValueElement.textContent = judulY;
                
                // Perbarui preview jika ada
                const judulContainer = document.getElementById('preview-judul-container');
                if (judulContainer) {
                    // Konversi posisi Y (1000-1800) ke posisi relatif dalam preview (40%-80%)
                    const percentage = (judulY - 1000) / 800; // 0-1
                    const topPosition = 40 + percentage * 40; // 40%-80%
                    judulContainer.style.top = topPosition + '%';
                }
            }
            
            // Fungsi untuk memperbarui tampilan posisi Y narasi
            function updateNarasiYPosition() {
                if (!narasiYRangeInput || !narasiYValueElement) return;
                
                const narasiY = parseInt(narasiYRangeInput.value);
                narasiYValueElement.textContent = narasiY;
                
                // Perbarui preview jika ada
                const narasiContainer = document.getElementById('preview-narasi-container');
                if (narasiContainer) {
                    // Konversi posisi Y (1400-1950) ke posisi relatif dalam preview (50%-90%)
                    const percentage = (narasiY - 1400) / 550; // 0-1
                    const topPosition = 50 + percentage * 40; // 50%-90%
                    narasiContainer.style.top = topPosition + '%';
                }
            }
            
            // Fungsi untuk memperbarui jarak antara judul dan narasi
            function updateGapValue() {
                if (!gapRangeInput || !gapValueElement) return;
                
                const gap = parseInt(gapRangeInput.value);
                gapValueElement.textContent = gap;
                
                // Update posisi narasi berdasarkan jarak jika judul_y dan narasi_y belum diatur
                // Hanya dilakukan jika slider posisi tidak aktif
                if (!judulYRangeInput.getAttribute('data-active') && !narasiYRangeInput.getAttribute('data-active')) {
                    const judulContainer = document.getElementById('preview-judul-container');
                    const narasiContainer = document.getElementById('preview-narasi-container');
                    
                    if (judulContainer && narasiContainer) {
                        // Ambil posisi judul saat ini
                        const judulPosition = parseFloat(judulContainer.style.top) || 65;
                        
                        // Hitung jarak gap dalam persentase (gap 50-500px dikonversi ke 5%-30%)
                        const gapPercentage = 5 + ((gap - 50) / 450) * 25;
                        
                        // Posisi narasi = posisi judul + gap
                        narasiContainer.style.top = (judulPosition + gapPercentage) + '%';
                    }
                }
            }
            
            // Inisialisasi tampilan awal
            if (judulYRangeInput) {
                updateJudulYPosition();
                
                // Event listener untuk slider judul Y
                judulYRangeInput.addEventListener('input', updateJudulYPosition);
                
                // Event listener untuk tombol kontrol judul Y
                if (judulYUpButton) {
                    judulYUpButton.addEventListener('click', function() {
                        const currentPos = parseInt(judulYRangeInput.value);
                        const newPos = Math.max(1000, currentPos - 10);
                        judulYRangeInput.value = newPos;
                        updateJudulYPosition();
                        
                        // Efek visual saat diklik
                        this.classList.add('bg-indigo-300');
                        setTimeout(() => {
                            this.classList.remove('bg-indigo-300');
                        }, 200);
                    });
                }
                
                if (judulYDownButton) {
                    judulYDownButton.addEventListener('click', function() {
                        const currentPos = parseInt(judulYRangeInput.value);
                        const newPos = Math.min(1800, currentPos + 10);
                        judulYRangeInput.value = newPos;
                        updateJudulYPosition();
                        
                        // Efek visual saat diklik
                        this.classList.add('bg-indigo-300');
                        setTimeout(() => {
                            this.classList.remove('bg-indigo-300');
                        }, 200);
                    });
                }
            }
            
            // Inisialisasi tampilan awal narasi
            if (narasiYRangeInput) {
                updateNarasiYPosition();
                
                // Event listener untuk slider narasi Y
                narasiYRangeInput.addEventListener('input', updateNarasiYPosition);
                
                // Event listener untuk tombol kontrol narasi Y
                if (narasiYUpButton) {
                    narasiYUpButton.addEventListener('click', function() {
                        const currentPos = parseInt(narasiYRangeInput.value);
                        const newPos = Math.max(1400, currentPos - 10);
                        narasiYRangeInput.value = newPos;
                        updateNarasiYPosition();
                        
                        // Efek visual saat diklik
                        this.classList.add('bg-indigo-300');
                        setTimeout(() => {
                            this.classList.remove('bg-indigo-300');
                        }, 200);
                    });
                }
                
                if (narasiYDownButton) {
                    narasiYDownButton.addEventListener('click', function() {
                        const currentPos = parseInt(narasiYRangeInput.value);
                        const newPos = Math.min(1950, currentPos + 10);
                        narasiYRangeInput.value = newPos;
                        updateNarasiYPosition();
                        
                        // Efek visual saat diklik
                        this.classList.add('bg-indigo-300');
                        setTimeout(() => {
                            this.classList.remove('bg-indigo-300');
                        }, 200);
                    });
                }
            }

            // Event listener untuk slider gap
            if (gapRangeInput) {
                gapRangeInput.addEventListener('input', function() {
                    updateGapValue();
                });
                
                // Event listener untuk tombol kontrol gap
                if (gapDecreaseButton) {
                    gapDecreaseButton.addEventListener('click', function() {
                        const currentGap = parseInt(gapRangeInput.value);
                        const newGap = Math.max(50, currentGap - 5);
                        gapRangeInput.value = newGap;
                        updateGapValue();
                        
                        // Efek visual saat diklik
                        this.classList.add('bg-indigo-300');
                        setTimeout(() => {
                            this.classList.remove('bg-indigo-300');
                        }, 200);
                    });
                }
                
                if (gapIncreaseButton) {
                    gapIncreaseButton.addEventListener('click', function() {
                        const currentGap = parseInt(gapRangeInput.value);
                        const newGap = Math.min(500, currentGap + 5);
                        gapRangeInput.value = newGap;
                        updateGapValue();
                        
                        // Efek visual saat diklik
                        this.classList.add('bg-indigo-300');
                        setTimeout(() => {
                            this.classList.remove('bg-indigo-300');
                        }, 200);
                    });
                }
            }
        });
    </script>
@endsection 