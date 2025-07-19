/**
 * Live Preview untuk Poster Image Generator
 * Menampilkan preview langsung saat memilih gambar dan frame
 */

document.addEventListener('DOMContentLoaded', function() {
    initLivePreview();
});

/**
 * Inisialisasi live preview
 */
function initLivePreview() {
    const gambarInput = document.getElementById('gambar');
    const frameInput = document.getElementById('frame');
    const judulInput = document.getElementById('judul');
    const narasiInput = document.getElementById('narasi');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    const previewFrame = document.getElementById('preview-frame');
    const previewJudul = document.getElementById('preview-judul');
    const previewNarasi = document.getElementById('preview-narasi');
    
    if (!gambarInput || !frameInput || !previewContainer) return;
    
    // Tampilkan area preview
    previewContainer.classList.remove('hidden');
    
    // Fungsi untuk menampilkan gambar preview
    gambarInput.addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('hidden');
                previewContainer.classList.remove('hidden');
                updatePreviewText(); // Update teks saat gambar berubah
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Fungsi untuk menampilkan frame preview
    frameInput.addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewFrame.src = e.target.result;
                previewFrame.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Update teks judul dan narasi
    function updatePreviewText() {
        if (judulInput && previewJudul) {
            previewJudul.textContent = judulInput.value || 'Judul Poster';
        }
        
        if (narasiInput && previewNarasi) {
            previewNarasi.textContent = narasiInput.value || 'Narasi poster akan ditampilkan di sini';
        }
    }
    
    // Event listener untuk input judul dan narasi
    if (judulInput) {
        judulInput.addEventListener('input', updatePreviewText);
    }
    
    if (narasiInput) {
        narasiInput.addEventListener('input', updatePreviewText);
    }
    
    // Initial update
    updatePreviewText();
} 