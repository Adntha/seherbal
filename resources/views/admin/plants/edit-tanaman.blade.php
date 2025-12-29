<section id="edit-tanaman" class="admin-section">
    <link rel="stylesheet" href="{{ asset('css/edit-tanaman.css') }}">
    
    <div class="top-nav">
        <a href="{{ route('admin.dashboard') }}" class="btn-back-link">Kembali</a>
    </div>

    <div class="container">
        <div class="form-card-minimal">
            <h2 class="form-title">Edit Tanaman Herbal</h2>
            
            <form id="editPlantForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="plant_id" value="{{ $plant->id }}">

                <div class="form-main-layout">
                    <div class="form-left">
                        <div class="input-minimal-group">
                            <label>Nama Lokal</label>
                            <input type="text" name="name" value="{{ $plant->name }}" placeholder="Contoh: Lidah Buaya" required>
                        </div>

                        <div class="input-minimal-group">
                            <label>Nama Latin</label>
                            <input type="text" name="latin_name" value="{{ $plant->latin_name }}" placeholder="Contoh: Aloe Vera" required>
                        </div>

                        <div class="input-minimal-group">
                            <label>Deskripsi</label>
                            <textarea name="description" rows="3" placeholder="Jelaskan ciri-ciri tanaman...">{{ $plant->description }}</textarea>
                        </div>

                        <div class="input-minimal-group">
                            <label>Manfaat</label>
                            <textarea name="benefits" rows="3" placeholder="Manfaat kesehatan...">{{ $plant->benefits }}</textarea>
                        </div>

                        <div class="input-minimal-group">
                            <label>Cara Penggunaan</label>
                            <textarea name="usage" rows="3" placeholder="Cara mengolah...">{{ $plant->usage }}</textarea>
                        </div>
                    </div>

                    <div class="form-right">
                        <label class="label-heading">Foto Tanaman</label>
                        <div class="image-upload-box">
                            <input type="file" id="imageInput" name="image" accept="image/*">
                            <div class="image-preview-area">
                                <img id="previewImg" src="{{ asset('storage/plants/' . $plant->image_path) }}" alt="Preview">
                                <div class="upload-hint">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <span>Klik untuk ganti gambar</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit-green">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const editForm = document.getElementById("editPlantForm");
            const imageInput = document.getElementById("imageInput");
            const previewImg = document.getElementById("previewImg");
            
            // Mengambil ID dari input hidden yang baru kita tambahkan
            const plantId = document.getElementById("plant_id").value;
            const API_URL = `/api/plants/${plantId}`;
            const authToken = localStorage.getItem("admin_token");

            // 1. Logika Preview Gambar
            imageInput.addEventListener("change", function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // 2. Logika Submit Update
            editForm.addEventListener("submit", async function (e) {
                e.preventDefault();

                const formData = new FormData(editForm);
                // Laravel spoofing method PUT
                formData.append("_method", "PUT");

                try {
                    const submitBtn = editForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = "Menyimpan...";
                    submitBtn.disabled = true;

                    const response = await fetch(API_URL, {
                        method: "POST", // POST + _method PUT = Update di Laravel
                        headers: {
                            "Authorization": `Bearer ${authToken}`,
                            "Accept": "application/json",
                            // Jangan set Content-Type secara manual saat menggunakan FormData
                        },
                        body: formData,
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert("Data tanaman berhasil diperbarui!");
                        window.location.href = "/admin/dashboard"; 
                    } else {
                        alert("Gagal update: " + (result.message || "Terjadi kesalahan"));
                        console.error(result.errors);
                    }
                } catch (error) {
                    console.error("Error updating plant:", error);
                    alert("Terjadi kesalahan koneksi ke server.");
                } finally {
                    const submitBtn = editForm.querySelector('button[type="submit"]');
                    submitBtn.textContent = "Simpan Perubahan";
                    submitBtn.disabled = false;
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const editForm = document.getElementById("editPlantForm");
            const imageInput = document.getElementById("imageInput");
            const previewImg = document.getElementById("previewImg");
            const submitBtn = document.getElementById("submitBtn");

            const plantId = document.getElementById("plant_id").value;
            const API_URL = `/api/plants/${plantId}`;
            const authToken = localStorage.getItem("admin_token");

            // 1. Live Preview Gambar saat File dipilih
            imageInput.addEventListener("change", function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // 2. Submit Update via Fetch API
            editForm.addEventListener("submit", async function (e) {
                e.preventDefault();

                const formData = new FormData(editForm);
                formData.append("_method", "PUT"); // Method spoofing Laravel

                try {
                    submitBtn.textContent = "Menyimpan...";
                    submitBtn.disabled = true;

                    const response = await fetch(API_URL, {
                        method: "POST", // Tetap POST karena FormData mengirim file
                        headers: {
                            "Authorization": `Bearer ${authToken}`,
                            "Accept": "application/json",
                        },
                        body: formData,
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert("Data tanaman berhasil diperbarui!");
                        window.location.href = "/admin/dashboard"; 
                    } else {
                        alert("Gagal: " + (result.message || "Terjadi kesalahan"));
                    }
                } catch (error) {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan koneksi.");
                } finally {
                    submitBtn.textContent = "Simpan Perubahan";
                    submitBtn.disabled = false;
                }
            });
        });
    </script>
    
</section>