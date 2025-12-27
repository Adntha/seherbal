document.addEventListener('DOMContentLoaded', () => {
    const article = document.querySelector('.plant-detail');
    const infoCards = document.querySelectorAll('.info-block');

    // Animasi masuk untuk artikel utama
    article.style.opacity = '0';
    article.style.transform = 'translateY(30px)';
    article.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';

    setTimeout(() => {
        article.style.opacity = '1';
        article.style.transform = 'translateY(0)';
    }, 150);

    // Efek interaksi pada card-style
    infoCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            if(card.classList.contains('card-style')) {
                card.style.borderColor = '#15803d';
                card.style.transition = '0.3s';
            }
        });
        
        card.addEventListener('mouseleave', () => {
            if(card.classList.contains('card-style')) {
                card.style.borderColor = '#f1f5f9';
            }
        });
    });
}); 