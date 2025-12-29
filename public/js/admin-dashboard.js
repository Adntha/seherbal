// ========================================
// ADMIN DASHBOARD - CRUD TANAMAN HERBAL
// ========================================

// Base URL API
const API_URL = "/api/plants";
let authToken = localStorage.getItem("admin_token");

// Load semua tanaman saat page load
document.addEventListener("DOMContentLoaded", function () {
    loadPlants();
    setupEventListeners();
});

// ========================================
// EVENT LISTENERS
// ========================================

function setupEventListeners() {
    // // Tombol tambah tanaman
    // const btnAdd = document.querySelector(".btn-add");
    // if (btnAdd) {
    //     btnAdd.addEventListener("click", showCreateModal);
    // }

    // Search input
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("input", handleSearch);
    }
}

// ========================================
// LOAD DATA DARI API
// ========================================

async function loadPlants() {
    try {
        const response = await fetch(API_URL);
        const result = await response.json();

        if (result.status === "success") {
            renderPlantsTable(result.data);
            updateStats(result.total);
        }
    } catch (error) {
        console.error("Error loading plants:", error);
        alert("Gagal memuat data tanaman");
    }
}

// ========================================
// RENDER TABLE
// ========================================

function renderPlantsTable(plants) {
    const tbody = document.querySelector(".data-table tbody");
    if (!tbody) return;

    tbody.innerHTML = "";

    if (plants.length === 0) {
        tbody.innerHTML =
            '<tr><td colspan="5" style="text-align:center; padding: 40px; color: #9ca3af;">Tidak ada data tanaman</td></tr>';
        return;
    }

    plants.forEach((plant) => {
        const row = `
            <tr>
                <td><img src="${
                    plant.image_url || "/images/placeholder.png"
                }" class="img-preview" alt="${
            plant.name
        }" onerror="this.src='/images/placeholder.png'"></td>
                <td>${plant.name}</td>
                <td>${plant.family}</td>
                <td><span class="status-badge terbit">Terbit</span></td>
                <td class="action-btns">
                    <button class="edit-btn" onclick="editPlant(${
                        plant.id
                    })" title="Edit">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </button>
                    <button class="delete-btn" onclick="deletePlant(${
                        plant.id
                    }, '${plant.name.replace(/'/g, "\\'")}')" title="Hapus">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
    });
}

// Update statistik
function updateStats(total) {
    const totalElement = document.querySelector(
        ".stats-box:first-of-type .stats-value"
    );
    if (totalElement) {
        totalElement.textContent = total;
    }
}

// ========================================
// CREATE PLANT
// ========================================

// function showCreateModal() {
//     alert(
//         "Fitur modal form akan ditambahkan. Untuk sementara, gunakan Postman atau cURL untuk testing API."
//     );
//     console.log("Create Plant - Endpoint: POST /api/plants");
//     console.log(
//         "Required fields: name, latin_name, family, part_used, keywords, description, benefits, processing, image"
//     );
// }

async function createPlant(formData) {
    try {
        const response = await fetch(API_URL, {
            method: "POST",
            headers: {
                Authorization: `Bearer ${authToken}`,
                Accept: "application/json",
            },
            body: formData,
        });

        const result = await response.json();

        if (result.status === "success") {
            alert(result.message);
            loadPlants(); // Reload table
            closeModal();
        } else {
            showErrors(result.errors);
        }
    } catch (error) {
        console.error("Error creating plant:", error);
        alert("Gagal menambahkan tanaman");
    }
}

// ========================================
// UPDATE PLANT
// ========================================

// Ganti fungsi editPlant yang lama dengan ini
function editPlant(id) {
    // Karena Laravel menggunakan named route admin.plants.edit-tanaman
    // Biasanya URL-nya berbentuk /admin/plants/{id}/edit
    window.location.href = `/admin/plants/${id}/edit`;
}

async function updatePlant(id, formData) {
    try {
        const response = await fetch(`${API_URL}/${id}`, {
            method: "POST",
            headers: {
                Authorization: `Bearer ${authToken}`,
                Accept: "application/json",
            },
            body: formData,
        });

        const result = await response.json();

        if (result.status === "success") {
            alert(result.message);
            loadPlants();
            closeModal();
        } else {
            showErrors(result.errors);
        }
    } catch (error) {
        console.error("Error updating plant:", error);
        alert("Gagal mengupdate tanaman");
    }
}

// ========================================
// DELETE PLANT
// ========================================

async function deletePlant(id) {
    if (confirm('Apakah Anda yakin ingin menghapus tanaman ini?')) {
        const response = await fetch(`/api/plants/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('admin_token')}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            location.reload(); // Refresh tabel
        }
    }
}

// ========================================
// SEARCH HANDLER
// ========================================

function handleSearch(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll(".data-table tbody tr");

    rows.forEach((row) => {
        const plantName = row.cells[1]?.textContent.toLowerCase() || "";
        const family = row.cells[2]?.textContent.toLowerCase() || "";

        if (plantName.includes(searchTerm) || family.includes(searchTerm)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

// ========================================
// HELPER FUNCTIONS
// ========================================

function showErrors(errors) {
    let errorMessage = "Terjadi kesalahan:\n\n";
    for (const [field, messages] of Object.entries(errors)) {
        errorMessage += `${field}: ${messages.join(", ")}\n`;
    }
    alert(errorMessage);
}

function closeModal() {
    // Implementasi close modal
    console.log("Modal closed");
}

// ========================================
// AUTH CHECK
// ========================================

// Cek apakah user sudah login
if (!authToken) {
    console.warn("No auth token found. CRUD operations will require login.");
}
