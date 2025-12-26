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
</script>