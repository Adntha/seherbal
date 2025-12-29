<section id="edit-tanaman" class="admin-section">
    <link rel="stylesheet" href="{{ asset('css/edit-tanaman.css') }}">
    
    <div class="top-nav">
        <a href="{{ route('admin.dashboard') }}" class="btn-back-link">Kembali</a>
    </div>

    <div class="container">
        <div class="form-card-minimal">
            <h2 class="form-title">Edit Tanaman Herbal</h2>
            
            <form id="editPlantForm" enctype="multipart/form-data">
                <!-- @csrf -->
                <input type="hidden" id="plant_id" value="{{ $plant->id }}">
                
                {{-- Hidden fields for required database columns --}}
                <input type="hidden" name="family" value="{{ $plant->family }}">
                <input type="hidden" name="part_used" value="{{ $plant->part_used }}">
                <input type="hidden" name="keywords" value="{{ $plant->keywords ?? '' }}">

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
                            <textarea name="processing" rows="3" placeholder="Cara mengolah...">{{ $plant->processing }}</textarea>
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
            const submitBtn = editForm.querySelector('button[type="submit"]'); // Added this line
            
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

                const formData = new FormData();
                
                // ✅ Add text fields
                formData.append('name', editForm.querySelector('[name="name"]').value);
                formData.append('latin_name', editForm.querySelector('[name="latin_name"]').value);
                formData.append('description', editForm.querySelector('[name="description"]').value);
                formData.append('benefits', editForm.querySelector('[name="benefits"]').value);
                formData.append('processing', editForm.querySelector('[name="processing"]').value);
                
                // ✅ Add required hidden fields
                formData.append('family', editForm.querySelector('[name="family"]').value);
                formData.append('part_used', editForm.querySelector('[name="part_used"]').value);
                formData.append('keywords', editForm.querySelector('[name="keywords"]').value);
                
                // ✅ Only add image if user selected a new file
                const imageInput = document.getElementById('imageInput');
                if (imageInput.files && imageInput.files[0]) {
                    formData.append('image', imageInput.files[0]);
                }

                // No method spoofing - route is POST

                // ✅ DEBUG: Log all form data
                console.log('=== DEBUGGING FORM SUBMISSION ===');
                console.log('API URL:', API_URL);
                console.log('Token:', authToken ? 'EXISTS (' + authToken.substring(0, 20) + '...)' : 'NULL');
                console.log('Form Data:');
                for (let [key, value] of formData.entries()) {
                    if (value instanceof File) {
                        console.log(`  ${key}: [FILE] ${value.name} (${value.size} bytes)`);
                    } else {
                        console.log(`  ${key}: ${value}`);
                    }
                }
                console.log('================================');

                try {
                    // const submitBtn = editForm.querySelector('button[type="submit"]'); // Removed this line as it's declared above
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
                    
                    console.log('Response Status:', response.status);
                    console.log('Response Data:', result);

                    if (response.ok) {
                        alert("Data tanaman berhasil diperbarui!");
                        window.location.href = "/admin/dashboard"; 
                    } else {
                        // ✅ Show detailed error
                        let errorMsg = "Gagal update:\n\n";
                        if (result.errors) {
                            for (let field in result.errors) {
                                errorMsg += `${field}: ${result.errors[field].join(', ')}\n`;
                            }
                        } else {
                            errorMsg += (result.message || "Terjadi kesalahan");
                        }
                        alert(errorMsg);
                        console.error('Full error response:', result);
                    }
                } catch (error) {
                    console.error("Error updating plant:", error);
                    alert("Terjadi kesalahan koneksi ke server.");
                } finally {
                    // const submitBtn = editForm.querySelector('button[type="submit"]'); // Removed this line
                    submitBtn.textContent = "Simpan Perubahan";
                    submitBtn.disabled = false;
                }
            });
        });
    </script>
    
</section>