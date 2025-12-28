document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const btnBrowse = document.getElementById('btnBrowse');

    // Trigger input file saat box preview atau tombol diklik
    [imagePreview, btnBrowse].forEach(el => {
        el.addEventListener('click', () => imageInput.click());
    });

    // Preview Gambar
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                imagePreview.style.border = "none";
            }
            reader.readAsDataURL(file);
        }
    });

    // Validasi sederhana sebelum kirim
    document.getElementById('plantForm').addEventListener('submit', function() {
        const btn = this.querySelector('.btn-primary');
        btn.innerText = 'Menyimpan...';
        btn.style.opacity = '0.7';
    });
});