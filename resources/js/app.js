import './bootstrap';

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const footer = document.getElementById('searchFooter');
    const grid = document.getElementById('herbalGrid');
    const formKontak = document.getElementById('formKontak');

    // --- 1. LOGIKA FORM KONTAK ---
    if (formKontak) {
        formKontak.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.querySelector('.btn-kirim');
            btn.innerText = 'Mengirim...';
            btn.disabled = true;

            setTimeout(() => {
                alert('Terima kasih! Pesan Anda telah terkirim.');
                this.reset();
                btn.innerText = 'Kirim Pesan';
                btn.disabled = false;
            }, 2000);
        });
    }

    // --- 2. LOGIKA LOAD MORE (TEMUKAN LEBIH BANYAK) ---
    if (footer) {
        footer.onclick = function() {
            fetch('/plants/all')
                .then(res => res.json())
                .then(data => {
                    grid.innerHTML = '';
                    data.forEach(item => {
                        // Bungkus dengan .card-link agar konsisten dengan data awal
                        grid.insertAdjacentHTML('beforeend', `
                            <a href="/tanaman/${item.slug}" class="card-link">
                                <div class="herbal-card">
                                    <img src="/storage/plants/${item.image_path}" alt="${item.name}">
                                    <div class="card-info">
                                        <h3>${item.name}</h3>
                                        <p>${item.latin_name}</p>
                                    </div>
                                </div>
                            </a>
                        `);
                    });
                    footer.style.display = 'none';
                })
                .catch(err => console.error("Gagal memuat data:", err));
        };
    }

    // --- 3. LOGIKA PENCARIAN REAL-TIME (RE-FLOW FIX) ---
    if (searchInput && herbalGrid) {
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            // KUNCINYA: Ambil semua .card-link, karena ini adalah anak langsung dari Grid
            const cardLinks = herbalGrid.querySelectorAll('.card-link');

            cardLinks.forEach(link => {
                const name = link.querySelector('h3').innerText.toLowerCase();
                const latin = link.querySelector('p').innerText.toLowerCase();
                
                if (name.includes(term) || latin.includes(term)) {
                    // Tampilkan kembali
                    link.style.display = "flex"; 
                } else {
                    // Sembunyikan TOTAL agar slot grid kosong tersebut hilang dan kartu lain naik
                    link.style.display = "none"; 
                }
            });
        });
    }
});
</script>