/**
 * Poster Features
 * Fitur-fitur untuk poster generator: Copy Narasi dan Download
 */

document.addEventListener('DOMContentLoaded', function() {
    initCopyNarasi();
    initDownloadButtons();
    initImageControls();
});

/**
 * Inisialisasi fitur copy narasi
 */
function initCopyNarasi() {
    const copyButtons = document.querySelectorAll('.copy-narasi-btn');
    
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const narasiText = this.getAttribute('data-narasi');
            copyToClipboard(narasiText, this);
        });
    });
}

/**
 * Salin teks ke clipboard
 * 
 * @param {string} text - Teks yang akan disalin
 * @param {HTMLElement} button - Tombol yang diklik
 */
function copyToClipboard(text, button) {
    // Simpan teks dan ikon original
    const originalText = button.innerHTML;
    
    navigator.clipboard.writeText(text)
        .then(() => {
            // Feedback sukses
            button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Berhasil Disalin!';
            button.classList.add('bg-green-600');
            button.classList.remove('bg-indigo-600');
            
            // Kembalikan ke status semula setelah 2 detik
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('bg-green-600');
                button.classList.add('bg-indigo-600');
            }, 2000);
        })
        .catch(() => {
            // Feedback error
            button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Gagal Menyalin';
            button.classList.add('bg-red-600');
            button.classList.remove('bg-indigo-600');
            
            // Kembalikan ke status semula setelah 2 detik
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('bg-red-600');
                button.classList.add('bg-indigo-600');
            }, 2000);
        });
}

/**
 * Inisialisasi tombol download
 */
function initDownloadButtons() {
    const downloadButtons = document.querySelectorAll('.download-image-btn');
    
    downloadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const downloadUrl = this.getAttribute('data-image');
            const fileName = this.getAttribute('data-filename') + '.jpg';
            
            // Tambahkan feedback visual saat download dimulai
            const originalText = button.innerHTML;
            
            // Tampilkan feedback unduhan dimulai
            button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Menyiapkan...';
            
            // Tampilkan notifikasi kecil tentang ukuran file
            const downloadNotif = document.createElement('div');
            downloadNotif.className = 'fixed bottom-4 right-4 bg-green-600 text-white p-4 rounded-lg shadow-lg z-50 animate-bounce';
            downloadNotif.innerHTML = `
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Mengunduh poster resolusi 2000×2000px</span>
                </div>
            `;
            document.body.appendChild(downloadNotif);
            
            // Simulasikan proses download dengan timer
            // Ini untuk UX saja, download sebenarnya sudah berjalan melalui atribut 'download'
            setTimeout(() => {
                button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Berhasil!';
                button.classList.add('bg-green-700');
                button.classList.remove('bg-green-600');
                
                // Kembalikan ke status semula setelah 2 detik
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-700');
                    button.classList.add('bg-green-600');
                    
                    // Hapus notifikasi
                    setTimeout(() => {
                        document.body.removeChild(downloadNotif);
                    }, 1500);
                }, 1500);
            }, 1500);
        });
    });
}

/**
 * Inisialisasi kontrol gambar (skala dan posisi) untuk admin page
 */
function initImageControls() {
    // Cek apakah elemen kontrol gambar ada (halaman admin atau edit)
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    const scaleRangeInput = document.getElementById('scale_gambar_range');
    
    if (!previewContainer || !previewImage || !scaleRangeInput) {
        return; // Keluar jika tidak di halaman yang memiliki kontrol gambar
    }
    
    // Tampilkan preview container
    previewContainer.classList.remove('hidden');
    
    // Elemen-elemen pengaturan gambar
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
    
    // Juga perbarui saat gambar atau frame berubah
    const gambarInput = document.getElementById('gambar');
    const frameInput = document.getElementById('frame');
    const previewFrame = document.getElementById('preview-frame');
    
    if (gambarInput) {
        gambarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImage) {
                        previewImage.src = e.target.result;
                        previewImage.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    if (frameInput && previewFrame) {
        frameInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewFrame.src = e.target.result;
                    previewFrame.classList.remove('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Juga perbarui judul dan narasi di preview
    const judulInput = document.getElementById('judul');
    const narasiInput = document.getElementById('narasi');
    const previewJudul = document.getElementById('preview-judul');
    const previewNarasi = document.getElementById('preview-narasi');
    
    if (judulInput && previewJudul) {
        judulInput.addEventListener('input', function() {
            previewJudul.textContent = this.value || 'Judul Poster';
        });
    }
    
    if (narasiInput && previewNarasi) {
        narasiInput.addEventListener('input', function() {
            let text = this.value || 'Narasi poster akan ditampilkan di sini';
            if (text.length > 100) {
                text = text.substring(0, 100) + '...';
            }
            previewNarasi.textContent = text;
        });
    }
    
    // Fungsi untuk memperbarui tampilan skala dan posisi gambar
    function updateImageTransform() {
        const scale = parseFloat(scaleRangeInput.value);
        const posX = parseInt(posXRangeInput.value);
        const posY = parseInt(posYRangeInput.value);
        
        // Transformasi gambar dengan CSS
        previewImage.style.transform = `translate(${posX}px, ${posY}px) scale(${scale})`;
        previewImage.style.transformOrigin = 'center';
        
        // Update nilai yang ditampilkan
        scaleValueElement.textContent = scale.toFixed(2);
        posXValueElement.textContent = posX;
        posYValueElement.textContent = posY;
    }
    
    // Inisialisasi tampilan awal
    updateImageTransform();
    
    // Event listener untuk slider skala dan posisi
    scaleRangeInput.addEventListener('input', updateImageTransform);
    posXRangeInput.addEventListener('input', updateImageTransform);
    posYRangeInput.addEventListener('input', updateImageTransform);
    
    // Tambahkan dukungan keyboard untuk kontrol nilai
    [scaleRangeInput, posXRangeInput, posYRangeInput].forEach(input => {
        input.addEventListener('keydown', function(e) {
            let step = 0;
            if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
                step = parseFloat(this.step || 1);
                this.value = Math.min(parseFloat(this.max), parseFloat(this.value) + step);
                e.preventDefault();
            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
                step = parseFloat(this.step || 1);
                this.value = Math.max(parseFloat(this.min), parseFloat(this.value) - step);
                e.preventDefault();
            }
            if (step !== 0) {
                // Trigger change event
                updateImageTransform();
            }
        });
    });
    
    // Event listener untuk tombol kontrol skala
    scaleDownButton.addEventListener('click', function() {
        const currentScale = parseFloat(scaleRangeInput.value);
        const newScale = Math.max(0.1, currentScale - 0.05).toFixed(2);
        scaleRangeInput.value = newScale;
        updateImageTransform();
        
        // Efek visual saat diklik
        this.classList.add('bg-indigo-300');
        setTimeout(() => {
            this.classList.remove('bg-indigo-300');
        }, 200);
    });
    
    scaleUpButton.addEventListener('click', function() {
        const currentScale = parseFloat(scaleRangeInput.value);
        const newScale = Math.min(2, currentScale + 0.05).toFixed(2);
        scaleRangeInput.value = newScale;
        updateImageTransform();
    });
    
    // Event listener untuk tombol kontrol posisi horizontal
    posXLeftButton.addEventListener('click', function() {
        const currentPos = parseInt(posXRangeInput.value);
        const newPos = Math.max(-500, currentPos - 10);
        posXRangeInput.value = newPos;
        updateImageTransform();
        
        // Efek visual saat diklik
        this.classList.add('bg-indigo-300');
        setTimeout(() => {
            this.classList.remove('bg-indigo-300');
        }, 200);
    });
    
    posXRightButton.addEventListener('click', function() {
        const currentPos = parseInt(posXRangeInput.value);
        const newPos = Math.min(500, currentPos + 10);
        posXRangeInput.value = newPos;
        updateImageTransform();
        
        // Efek visual saat diklik
        this.classList.add('bg-indigo-300');
        setTimeout(() => {
            this.classList.remove('bg-indigo-300');
        }, 200);
    });
    
    // Event listener untuk tombol kontrol posisi vertikal
    posYUpButton.addEventListener('click', function() {
        const currentPos = parseInt(posYRangeInput.value);
        const newPos = Math.max(-500, currentPos - 10);
        posYRangeInput.value = newPos;
        updateImageTransform();
        
        // Efek visual saat diklik
        this.classList.add('bg-indigo-300');
        setTimeout(() => {
            this.classList.remove('bg-indigo-300');
        }, 200);
    });
    
    posYDownButton.addEventListener('click', function() {
        const currentPos = parseInt(posYRangeInput.value);
        const newPos = Math.min(500, currentPos + 10);
        posYRangeInput.value = newPos;
        updateImageTransform();
        
        // Efek visual saat diklik
        this.classList.add('bg-indigo-300');
        setTimeout(() => {
            this.classList.remove('bg-indigo-300');
        }, 200);
    });
    
    // Reset posisi dan skala
    resetPositionButton.addEventListener('click', function() {
        scaleRangeInput.value = 1.0;
        posXRangeInput.value = 0;
        posYRangeInput.value = 0;
        updateImageTransform();
    });
} 