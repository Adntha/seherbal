document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const btnBrowse = document.getElementById('btnBrowse');

    // 1. Klik pada area kotak preview akan membuka file manager
    if (imagePreview) {
        imagePreview.addEventListener('click', function() {
            imageInput.click();
        });
    }

    // 2. Klik pada tombol "Pilih Foto" juga membuka file manager
    if (btnBrowse) {
        btnBrowse.addEventListener('click', function() {
            imageInput.click();
        });
    }

    // 3. Logika Preview Gambar (Agar gambar muncul setelah dipilih)
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            
            // Saat file selesai dibaca oleh browser
            reader.onload = function(e) {
                // Ganti isi dalam kotak preview dengan tag <img>
                imagePreview.innerHTML = `
                    <img src="${e.target.result}" 
                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                `;
                imagePreview.style.border = "none"; // Hilangkan border dashed jika perlu
            }
            
            reader.readAsDataURL(file);
        }
    });
});