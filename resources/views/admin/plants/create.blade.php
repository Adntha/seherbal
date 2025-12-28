<div class="dashboard-container">
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
                    <label>Foto Tanaman</label>
                    <div id="imagePreview" class="preview-container">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <p>Klik atau Seret Gambar</p>
                    </div>
                    <input type="file" id="imageInput" name="image" accept="image/*" hidden required>
                    <button type="button" id="btnBrowse" class="btn-browse">Pilih Foto</button>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Simpan Tanaman</button>
                </div>
            </div>
        </div>
    </form>
</div>