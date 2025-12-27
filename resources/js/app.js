import './bootstrap';

<script>
document.getElementById('formKontak').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Animasi sederhana saat tombol diklik
    const btn = document.querySelector('.btn-kirim');
    btn.innerText = 'Mengirim...';
    btn.disabled = true;

    // Simulasi pengiriman (bisa kamu ganti dengan fetch ke Laravel nantinya)
    setTimeout(() => {
        alert('Terima kasih! Pesan Anda telah terkirim.');
        this.reset();
        btn.innerText = 'Kirim Pesan';
        btn.disabled = false;
    }, 2000);
});

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const footer = document.getElementById('searchFooter');
    const grid = document.getElementById('herbalGrid');

    if (footer) {
        footer.onclick = function() {
            // Gunakan path langsung /plants/all 
            // Tidak perlu {{ }} di sini
            fetch('/plants/all')
                .then(res => res.json())
                .then(data => {
                    grid.innerHTML = '';
                    data.forEach(item => {
                        grid.insertAdjacentHTML('beforeend', `
                            <div class="herbal-card">
                                <img src="/storage/plants/${item.image_path}" alt="${item.name}">
                                <div class="card-info">
                                    <h3>${item.name}</h3>
                                    <p>${item.latin_name}</p>
                                </div>
                            </div>
                        `);
                    });
                    footer.style.display = 'none';
                });
        };
    }

    // Logika pencarian real-time
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const filter = searchInput.value.toLowerCase();
            const cards = grid.getElementsByClassName('herbal-card');

            Array.from(cards).forEach(card => {
                const name = card.querySelector('h3').textContent.toLowerCase();
                const latin = card.querySelector('p').textContent.toLowerCase();
                card.style.display = (name.includes(filter) || latin.includes(filter)) ? "" : "none";
            });
        });
    }
});
</script>