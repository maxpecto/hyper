// Cars Page JavaScript

const filterButtons = document.querySelectorAll('.filter-btn');
const carCards = document.querySelectorAll('.car-card');
const searchInput = document.getElementById('carSearch');
const searchBtn = document.querySelector('.search-btn');

let currentFilter = 'all';
let currentSearch = '';

// Filter functionality
filterButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Remove active class from all buttons
        filterButtons.forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        button.classList.add('active');
        
        // Get filter value
        currentFilter = button.getAttribute('data-filter');
        
        // Apply filters
        applyFilters();
        
        // Play sound effect
        playFilterSound();
    });
});

// Search functionality
searchInput.addEventListener('input', (e) => {
    currentSearch = e.target.value.toLowerCase();
    debounceSearch();
});

searchBtn.addEventListener('click', () => {
    applyFilters();
});

// Debounced search
let searchTimeout;
function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 300);
}

// Apply filters and search
function applyFilters() {
    let visibleCount = 0;
    
    carCards.forEach(card => {
        const category = card.getAttribute('data-category');
        const carTitle = card.querySelector('.car-title').textContent.toLowerCase();
        const carOwner = card.querySelector('.car-owner span').textContent.toLowerCase();
        const carSpecs = Array.from(card.querySelectorAll('.spec-value')).map(spec => spec.textContent.toLowerCase()).join(' ');
        const carTags = Array.from(card.querySelectorAll('.tag')).map(tag => tag.textContent.toLowerCase()).join(' ');
        
        const searchableText = `${carTitle} ${carOwner} ${carSpecs} ${carTags}`;
        
        // Check filter
        const passesFilter = currentFilter === 'all' || category === currentFilter;
        
        // Check search
        const passesSearch = !currentSearch || searchableText.includes(currentSearch);
        
        if (passesFilter && passesSearch) {
            showCard(card, visibleCount * 100);
            visibleCount++;
        } else {
            hideCard(card);
        }
    });
    
    // Show no results message
    updateResultsMessage(visibleCount);
}

// Show card with animation
function showCard(card, delay = 0) {
    card.style.display = 'block';
    
    setTimeout(() => {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0) scale(1)';
    }, delay);
}

// Hide card with animation
function hideCard(card) {
    card.style.opacity = '0';
    card.style.transform = 'translateY(-20px) scale(0.9)';
    
    setTimeout(() => {
        card.style.display = 'none';
    }, 300);
}

// Update results message
function updateResultsMessage(count) {
    let messageElement = document.querySelector('.results-message');
    
    if (count === 0) {
        if (!messageElement) {
            messageElement = document.createElement('div');
            messageElement.className = 'results-message';
            messageElement.style.cssText = `
                text-align: center;
                padding: 3rem;
                color: var(--text-gray);
                font-size: 1.2rem;
                grid-column: 1 / -1;
            `;
            document.querySelector('.cars-grid').appendChild(messageElement);
        }
        
        messageElement.innerHTML = `
            <div style="font-size: 3rem; margin-bottom: 1rem;">🔍</div>
            <p>Arama kriterlerinize uygun araç bulunamadı.</p>
            <p style="font-size: 0.9rem; margin-top: 0.5rem;">Farklı filtreler veya arama terimleri deneyin.</p>
        `;
        messageElement.style.display = 'block';
    } else {
        if (messageElement) {
            messageElement.style.display = 'none';
        }
    }
}

// Car details modal
document.querySelectorAll('.view-details').forEach(button => {
    button.addEventListener('click', (e) => {
        e.stopPropagation();
        const carCard = button.closest('.car-card');
        showCarDetails(carCard);
    });
});

function showCarDetails(carCard) {
    const carTitle = carCard.querySelector('.car-title').textContent;
    const carOwner = carCard.querySelector('.car-owner span').textContent;
    const carImage = carCard.querySelector('.car-image img').src;
    const carRating = carCard.querySelector('.stars').textContent;
    const carSpecs = Array.from(carCard.querySelectorAll('.spec-item')).map(spec => ({
        label: spec.querySelector('.spec-label').textContent,
        value: spec.querySelector('.spec-value').textContent
    }));
    const carTags = Array.from(carCard.querySelectorAll('.tag')).map(tag => tag.textContent);
    
    const modal = createCarModal({
        title: carTitle,
        owner: carOwner,
        image: carImage,
        rating: carRating,
        specs: carSpecs,
        tags: carTags
    });
    
    document.body.appendChild(modal);
    
    // Add event listeners
    const closeBtn = modal.querySelector('.modal-close');
    const modalOverlay = modal.querySelector('.modal-overlay');
    
    closeBtn.addEventListener('click', () => closeModal(modal));
    modalOverlay.addEventListener('click', () => closeModal(modal));
    
    // Animate modal in
    setTimeout(() => {
        modal.classList.add('active');
    }, 10);
}

function createCarModal({ title, owner, image, rating, specs, tags }) {
    const modal = document.createElement('div');
    modal.className = 'car-modal';
    modal.innerHTML = `
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2>${title}</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-car-image">
                    <img src="${image}" alt="${title}">
                </div>
                <div class="modal-car-info">
                    <div class="modal-owner">
                        <strong>Sahibi:</strong> ${owner}
                    </div>
                    <div class="modal-rating">
                        <strong>Değerlendirme:</strong> ${rating}
                    </div>
                    <div class="modal-specs">
                        <h3>Teknik Özellikler</h3>
                        <div class="specs-grid">
                            ${specs.map(spec => `
                                <div class="spec-item">
                                    <span class="spec-label">${spec.label}</span>
                                    <span class="spec-value">${spec.value}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    <div class="modal-tags">
                        <h3>Etiketler</h3>
                        <div class="tags-container">
                            ${tags.map(tag => `<span class="tag">${tag}</span>`).join('')}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-primary">Beğen ❤️</button>
                <button class="btn-secondary">Sahip ile İletişim</button>
                <button class="btn-secondary modal-close">Kapat</button>
            </div>
        </div>
    `;
    
    return modal;
}

function closeModal(modal) {
    modal.classList.remove('active');
    setTimeout(() => {
        if (document.body.contains(modal)) {
            document.body.removeChild(modal);
        }
    }, 300);
}

// Add modal styles
const modalStyles = document.createElement('style');
modalStyles.textContent = `
    .car-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .car-modal.active {
        opacity: 1;
        visibility: visible;
    }
    
    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
    }
    
    .car-modal .modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(10, 10, 10, 0.95);
        border: 1px solid rgba(255, 0, 255, 0.3);
        border-radius: 15px;
        max-width: 800px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-car-image {
        width: 100%;
        height: 300px;
        overflow: hidden;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    
    .modal-car-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .modal-car-info {
        padding: 0 1rem;
    }
    
    .modal-owner, .modal-rating {
        margin-bottom: 1rem;
        color: var(--text-light);
    }
    
    .modal-specs h3, .modal-tags h3 {
        font-family: 'Orbitron', monospace;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .specs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .modal-specs .spec-item {
        background: rgba(255, 255, 255, 0.05);
        padding: 1rem;
        border-radius: 10px;
        text-align: center;
    }
    
    .modal-specs .spec-label {
        display: block;
        color: var(--text-gray);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .modal-specs .spec-value {
        display: block;
        color: var(--text-light);
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .tags-container {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    
    .modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
`;
document.head.appendChild(modalStyles);

// Hover effects for car cards
carCards.forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-10px) scale(1.02)';
        
        // Add neon glow effect
        const image = card.querySelector('.car-image img');
        image.style.filter = 'brightness(1.1) contrast(1.1)';
        
        // Animate car specs
        const specs = card.querySelectorAll('.spec-item');
        specs.forEach((spec, index) => {
            setTimeout(() => {
                spec.style.transform = 'scale(1.05)';
                spec.style.color = 'var(--primary-color)';
            }, index * 50);
        });
    });
    
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0) scale(1)';
        
        const image = card.querySelector('.car-image img');
        image.style.filter = '';
        
        const specs = card.querySelectorAll('.spec-item');
        specs.forEach(spec => {
            spec.style.transform = 'scale(1)';
            spec.style.color = '';
        });
    });
});

// Rating interaction
document.querySelectorAll('.car-rating').forEach(rating => {
    rating.addEventListener('click', () => {
        const stars = rating.querySelector('.stars');
        stars.style.animation = 'starGlow 0.5s ease';
        playSuccessSound();
        
        setTimeout(() => {
            stars.style.animation = '';
        }, 500);
    });
});

// Add star glow animation
const starStyles = document.createElement('style');
starStyles.textContent = `
    @keyframes starGlow {
        0%, 100% { transform: scale(1); text-shadow: none; }
        50% { transform: scale(1.2); text-shadow: 0 0 20px #FFD700; }
    }
`;
document.head.appendChild(starStyles);

// Infinite scroll simulation
let isLoading = false;
window.addEventListener('scroll', () => {
    if (isLoading) return;
    
    const scrollPosition = window.innerHeight + window.scrollY;
    const threshold = document.body.offsetHeight - 1000;
    
    if (scrollPosition >= threshold) {
        loadMoreCars();
    }
});

function loadMoreCars() {
    isLoading = true;
    
    // Show loading indicator
    const loadingIndicator = document.createElement('div');
    loadingIndicator.className = 'loading-indicator';
    loadingIndicator.innerHTML = `
        <div style="text-align: center; padding: 2rem; color: var(--text-gray);">
            <div style="width: 40px; height: 40px; border: 3px solid rgba(255, 0, 255, 0.3); border-top: 3px solid var(--primary-color); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
            <p>Daha fazla araç yükleniyor...</p>
        </div>
    `;
    document.querySelector('.cars-gallery .container').appendChild(loadingIndicator);
    
    // Simulate loading
    setTimeout(() => {
        loadingIndicator.remove();
        showNotification('Tüm araçlar yüklendi!', 'info');
        isLoading = false;
    }, 2000);
}

// Sound effects
function playFilterSound() {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
    oscillator.frequency.exponentialRampToValueAtTime(1000, audioContext.currentTime + 0.1);
    
    gainNode.gain.setValueAtTime(0.05, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.1);
}

function playSuccessSound() {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.setValueAtTime(523, audioContext.currentTime);
    oscillator.frequency.setValueAtTime(659, audioContext.currentTime + 0.1);
    
    gainNode.gain.setValueAtTime(0.08, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.2);
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'info' ? 'rgba(0, 255, 255, 0.1)' : 'rgba(255, 0, 255, 0.1)'};
        color: ${type === 'info' ? 'var(--secondary-color)' : 'var(--primary-color)'};
        border: 1px solid ${type === 'info' ? 'var(--secondary-color)' : 'var(--primary-color)'};
        padding: 1rem 1.5rem;
        border-radius: 5px;
        box-shadow: ${type === 'info' ? 'var(--shadow-cyan)' : 'var(--shadow-neon)'};
        z-index: 10000;
        animation: slideIn 0.3s ease;
        max-width: 300px;
        font-family: 'Exo 2', sans-serif;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Initialize page
document.addEventListener('DOMContentLoaded', () => {
    // Initial filter application
    applyFilters();
    
    // Add loading animation to cars
    carCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Focus search input
    setTimeout(() => {
        if (searchInput) {
            searchInput.focus();
        }
    }, 1000);
}); 