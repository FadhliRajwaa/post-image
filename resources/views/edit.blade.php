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
                                maxlength="200" required>{{ old('narasi', $poster->narasi) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Maksimal 200 karakter</p>
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
                            <div class="absolute bottom-0 left-0 right-0 p-4 z-20">
                                <!-- Menggunakan font Arial Black untuk judul dan Calibri Bold untuk narasi -->
                                <h4 id="preview-judul" class="text-center text-white text-2xl mb-1 drop-shadow-lg" style="font-family: 'Arial Black', 'Arial Bold', sans-serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">{{ $poster->judul }}</h4>
                                <p id="preview-narasi" class="text-center text-white text-base drop-shadow-lg" style="font-family: 'Calibri Bold', 'Calibri', sans-serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">{{ Str::limit($poster->narasi, 100) }}</p>
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
            
            // Update judul saat input berubah
            judulInput.addEventListener('input', function() {
                previewJudul.textContent = this.value || '{{ $poster->judul }}';
            });
            
            // Update narasi saat input berubah
            narasiInput.addEventListener('input', function() {
                let text = this.value || '{{ $poster->narasi }}';
                if (text.length > 100) {
                    text = text.substring(0, 100) + '...';
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
        });
    </script>
@endsection 