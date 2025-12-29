<div class="dashboard-container">
    {{-- Pindahkan CSS ke paling atas atau gunakan @push --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard-form.css') }}">

    <header class="form-header">
        <h2><i class="fa-solid fa-plus-circle"></i> Tambah Tanaman Herbal</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Kembali</a>
    </header>

    <form id="plantForm" action="{{ route('admin.plants.store') }}" method="POST" enctype="multipart/form-data" class="modern-form">
        @csrf
        <div class="form-grid">
            <div class="form-main">
                <div class="input-group">
                    <label for="name">Nama Lokal</label>
                    <input type="text" id="name" name="name" placeholder="Contoh: Lidah Buaya" required>
                </div>

                <div class="input-group">
                    <label for="latin_name">Nama Latin</label>
                    <input type="text" id="latin_name" name="latin_name" placeholder="Contoh: Aloe Vera" required>
                </div>

                <div class="input-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" placeholder="Jelaskan ciri-ciri tanaman..."></textarea>
                </div>

                <div class="input-grid">
                    <div class="input-group">
                        <label for="benefits">Manfaat</label>
                        <textarea id="benefits" name="benefits" rows="3" placeholder="Manfaat kesehatan..."></textarea>
                    </div>
                    <div class="input-group">
                        <label for="usage">Cara Penggunaan</label>
                        <textarea id="usage" name="usage" rows="3" placeholder="Cara mengolah..."></textarea>
                    </div>
                </div>
            </div>

            <div class="form-sidebar">
                <div class="image-upload-box">
                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Foto Tanaman</label>
                    
                    <label for="imageInput" class="preview-container" style="cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; border: 2px dashed #ccc; min-height: 200px; padding: 10px; border-radius: 10px;">
                        <div id="imagePreview" style="text-align: center;">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2rem; color: #666;"></i>
                            <p style="margin-top: 10px; color: #666;">Klik untuk pilih gambar</p>
                        </div>
                    </label>

                    <input type="file" id="imageInput" name="image" accept="image/*" 
                        onchange="previewSimple(this)"
                        style="opacity: 0; position: absolute; z-index: -1;">
                </div>

                <div class="form-actions" style="margin-top: 20px;">
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 12px; background: #15803d; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                        Simpan Tanaman
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Variabel untuk menyimpan file terakhir yang berhasil dipilih
let lastValidFile = null;

function previewSimple(input) {
    const previewArea = document.getElementById('imagePreview');
    const btnBrowse = document.getElementById('btnBrowse');
    const file = input.files[0];

    if (file) {
        // Jika user memilih file baru yang valid
        lastValidFile = file; // Simpan sebagai cadangan terakhir
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewArea.innerHTML = `<img src="${e.target.result}" style="width:100%; max-height:250px; object-fit:cover; border-radius:8px;">`;
            btnBrowse.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        // Jika user klik ganti lalu klik "Cancel" (input menjadi kosong)
        if (lastValidFile) {
            // Kembalikan file cadangan ke dalam input file agar tidak kosong saat disubmit
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(lastValidFile);
            input.files = dataTransfer.files;
            
            console.log("Pemilihan dibatalkan, mengembalikan foto sebelumnya.");
        }
    }
}
</script>