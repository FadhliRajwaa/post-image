/**
 * Poster Features
 * Fitur-fitur untuk poster generator: Copy Narasi dan Download
 */

document.addEventListener('DOMContentLoaded', function() {
    initCopyNarasi();
    initDownloadButtons();
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