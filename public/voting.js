// Voting System JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('codeModal');
    const closeBtn = document.querySelector('.close');
    const votingCodeInput = document.getElementById('votingCode');
    const verifyCodeBtn = document.getElementById('verifyCodeBtn');
    const codeMessage = document.getElementById('codeMessage');
    const voteButtons = document.querySelectorAll('.vote-btn');
    
    let verifiedCodeId = null;
    let selectedCarId = null;

    // Cookie işlemleri
    function setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function hasVoted() {
        return getCookie('hyperdrive_voted') === 'true';
    }

    function markAsVoted() {
        setCookie('hyperdrive_voted', 'true', 30); // 30 gün geçerli
    }

    // Oy vermiş kullanıcıları kontrol et
    if (hasVoted()) {
        voteButtons.forEach(button => {
            button.disabled = true;
            button.innerHTML = '<span class="vote-icon">✅</span><span class="vote-text">Oy Verdiniz</span>';
            button.classList.add('voted');
        });
        
        // Bilgilendirme mesajı göster
        showNotification('Siz artıq oy vermisiniz. Təşəkkür edirik!', 'info');
    }

    // Modal açma/kapama
    function openModal() {
        // Eğer zaten oy vermişse modal açma
        if (hasVoted()) {
            showNotification('Siz artıq oy vermisiniz.', 'warning');
            return;
        }
        
        modal.style.display = 'block';
        votingCodeInput.focus();
        codeMessage.textContent = '';
        codeMessage.className = 'message';
    }

    function closeModal() {
        modal.style.display = 'none';
        // verifiedCodeId'yi sıfırlama - sadece modal'ı kapat
        votingCodeInput.value = '';
        codeMessage.textContent = '';
        codeMessage.className = 'message';
    }

    // Close button click
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    // Modal dışına tıklama
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Kod doğrulama
    verifyCodeBtn.addEventListener('click', function() {
        const code = votingCodeInput.value.trim().toUpperCase();
        
        if (!code) {
            showMessage('Zəhmət olmasa kod daxil edin.', 'error');
            return;
        }

        verifyCodeBtn.disabled = true;
        verifyCodeBtn.textContent = 'Yoxlanılır...';

        fetch('/voting/verify-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                verifiedCodeId = data.code_id;
                showMessage(data.message, 'success');
                verifyCodeBtn.textContent = 'Kod Təsdiqləndi';
                verifyCodeBtn.disabled = true;
                
                // 2 saniye sonra modal'ı kapat ve oy verme işlemini başlat
                setTimeout(() => {
                    closeModal();
                    if (selectedCarId) {
                        submitVote(selectedCarId);
                    }
                }, 2000);
            } else {
                showMessage(data.message, 'error');
                verifyCodeBtn.disabled = false;
                verifyCodeBtn.textContent = 'Kodu Təsdiqlə';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Xəta baş verdi. Zəhmət olmasa yenidən cəhd edin.', 'error');
            verifyCodeBtn.disabled = false;
            verifyCodeBtn.textContent = 'Kodu Təsdiqlə';
        });
    });

    // Enter tuşu ile kod doğrulama
    votingCodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            verifyCodeBtn.click();
        }
    });

    // Oy verme butonları
    voteButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Eğer zaten oy vermişse işlemi durdur
            if (hasVoted()) {
                showNotification('Siz artıq oy vermisiniz.', 'warning');
                return;
            }
            
            const carId = this.getAttribute('data-car-id');
            selectedCarId = carId;
            
            if (!verifiedCodeId) {
                // Kod doğrulanmamışsa modal aç
                openModal();
            } else {
                // Kod zaten doğrulanmışsa direkt oy ver
                submitVote(carId);
            }
        });
    });

    // Oy verme işlemi
    function submitVote(carId) {
        if (!verifiedCodeId) {
            showMessage('Zəhmət olmasa əvvəlcə kodunuzu təsdiqləyin.', 'error');
            return;
        }

        const button = document.querySelector(`[data-car-id="${carId}"] .vote-btn`);
        const originalText = button.innerHTML;
        
        button.disabled = true;
        button.innerHTML = '<span class="vote-icon">⏳</span><span class="vote-text">Göndərilir...</span>';

        fetch('/voting/vote', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                code_id: verifiedCodeId,
                registration_id: carId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Başarılı oy verme
                button.innerHTML = '<span class="vote-icon">✅</span><span class="vote-text">Oy Verildi</span>';
                button.classList.add('voted');
                button.disabled = true;
                
                // Tüm oy butonlarını devre dışı bırak
                voteButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="vote-icon">✅</span><span class="vote-text">Oy Verdiniz</span>';
                    btn.classList.add('voted');
                });
                
                // Cookie'ye oy verdiğini kaydet
                markAsVoted();
                
                // İstatistikleri güncelle
                updateStats(data.stats);
                
                // Başarı mesajı göster
                showNotification(data.message, 'success');
                
                // Kod kullanıldı, sıfırla
                verifiedCodeId = null;
                selectedCarId = null;
                
                // 3 saniye sonra sayfayı yenile
                setTimeout(() => {
                    location.reload();
                }, 3000);
                
            } else {
                // Hata durumu
                button.disabled = false;
                button.innerHTML = originalText;
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.disabled = false;
            button.innerHTML = originalText;
            showMessage('Xəta baş verdi. Zəhmət olmasa yenidən cəhd edin.', 'error');
        });
    }

    // Mesaj gösterme
    function showMessage(message, type) {
        codeMessage.textContent = message;
        codeMessage.className = `message ${type}`;
    }

    // Bildirim gösterme
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        
        // Stil ekle
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            font-weight: 600;
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;
        
        if (type === 'success') {
            notification.style.backgroundColor = '#10b981';
        } else if (type === 'error') {
            notification.style.backgroundColor = '#ef4444';
        } else if (type === 'warning') {
            notification.style.backgroundColor = '#f59e0b';
        } else if (type === 'info') {
            notification.style.backgroundColor = '#3b82f6';
        }
        
        document.body.appendChild(notification);
        
        // 5 saniye sonra kaldır
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // İstatistikleri güncelle
    function updateStats(stats) {
        if (stats) {
            const totalVotesElement = document.getElementById('totalVotes');
            const usedCodesElement = document.getElementById('usedCodes');
            
            if (totalVotesElement) {
                totalVotesElement.textContent = stats.total_votes;
            }
            
            if (usedCodesElement) {
                usedCodesElement.textContent = stats.used_codes;
            }
        }
    }

    // Gerçek zamanlı istatistik güncelleme (opsiyonel)
    function updateStatsPeriodically() {
        fetch('/voting/stats')
            .then(response => response.json())
            .then(data => {
                const totalVotesElement = document.getElementById('totalVotes');
                const totalCarsElement = document.getElementById('totalCars');
                const usedCodesElement = document.getElementById('usedCodes');
                
                if (totalVotesElement) totalVotesElement.textContent = data.total_votes;
                if (totalCarsElement) totalCarsElement.textContent = data.total_cars;
                if (usedCodesElement) usedCodesElement.textContent = data.used_codes;
                
                // Araç oy sayılarını güncelle
                data.cars.forEach(car => {
                    const voteElement = document.querySelector(`[data-car-id="${car.id}"] .votes`);
                    const percentageElement = document.querySelector(`[data-car-id="${car.id}"] .percentage-text`);
                    const percentageFillElement = document.querySelector(`[data-car-id="${car.id}"] .percentage-fill`);
                    
                    if (voteElement) voteElement.textContent = car.votes;
                    if (percentageElement) percentageElement.textContent = car.percentage + '%';
                    if (percentageFillElement) percentageFillElement.style.width = car.percentage + '%';
                });
            })
            .catch(error => console.error('Stats update error:', error));
    }

    // Her 30 saniyede bir istatistikleri güncelle
    setInterval(updateStatsPeriodically, 30000);
});

// CSS Animasyonları
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
    }
    
    .modal-content {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.9) 0%, rgba(255, 19, 241, 0.1) 50%, rgba(0, 255, 255, 0.1) 100%);
        border: 1px solid rgba(255, 19, 241, 0.3);
        border-radius: 15px;
        margin: 15% auto;
        padding: 0;
        width: 90%;
        max-width: 500px;
        position: relative;
        animation: modalSlideIn 0.3s ease;
    }
    
    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .modal-header {
        padding: 20px;
        border-bottom: 1px solid rgba(255, 19, 241, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-header h2 {
        color: var(--primary-color);
        margin: 0;
        font-family: 'Orbitron', monospace;
    }
    
    .close {
        color: var(--primary-color);
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .close:hover {
        color: var(--text-light);
        transform: scale(1.1);
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-body p {
        color: var(--text-gray);
        margin-bottom: 20px;
    }
    
    .message {
        margin: 10px 0;
        padding: 10px;
        border-radius: 5px;
        font-weight: 600;
    }
    
    .message.success {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .message.error {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .vote-btn.voted {
        background: var(--gradient-primary);
        color: var(--text-light);
        cursor: not-allowed;
    }
    
    .no-cars-message {
        text-align: center;
        padding: 50px 20px;
        color: var(--text-gray);
    }
    
    .no-cars-message h3 {
        color: var(--primary-color);
        margin-bottom: 10px;
    }
`;
document.head.appendChild(style); 