// Sponsors Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize counters
    initCounters();
    
    // Initialize sponsor cards
    initSponsorCards();
    
    // Initialize modal
    initModal();
});

// Counter Animation
function initCounters() {
    const counters = document.querySelectorAll('.stat-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps
        let current = 0;
        
        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.textContent = Math.floor(current).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString();
            }
        };
        
        // Start animation when element is in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCounter();
                    observer.unobserve(entry.target);
                }
            });
        });
        
        observer.observe(counter);
    });
}

// Sponsor Cards
function initSponsorCards() {
    const cards = document.querySelectorAll('.sponsor-card');
    
    cards.forEach(card => {
        card.addEventListener('click', function() {
            const sponsorId = this.getAttribute('data-sponsor-id');
            openSponsorModal(sponsorId);
        });
        
        // Add hover sound effect
        card.addEventListener('mouseenter', function() {
            playHoverSound();
        });
    });
}

// Modal Functions
function initModal() {
    const modal = document.getElementById('sponsorModal');
    const closeBtn = modal.querySelector('.modal-close');
    const overlay = modal.querySelector('.modal-overlay');
    
    // Close modal
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Event listeners
    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            closeModal();
        }
    });
}

// Open sponsor modal
function openSponsorModal(sponsorId) {
    const modal = document.getElementById('sponsorModal');
    
    // Show loading state
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Fetch sponsor data (in real app, this would be an API call)
    // For now, we'll use mock data
    const mockData = getMockSponsorData(sponsorId);
    
    // Update modal content
    updateModalContent(mockData);
    
    // Add entrance animation
    const modalContent = modal.querySelector('.modal-content');
    modalContent.style.transform = 'scale(0.8)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    }, 100);
}

// Update modal content
function updateModalContent(data) {
    const modal = document.getElementById('sponsorModal');
    
    // Update logo
    const logo = modal.querySelector('#modalLogo');
    if (data.logo) {
        logo.src = data.logo;
        logo.style.display = 'block';
    } else {
        logo.style.display = 'none';
    }
    
    // Update title
    modal.querySelector('#modalTitle').textContent = data.name;
    
    // Update category
    const categoryEl = modal.querySelector('#modalCategory');
    categoryEl.textContent = data.category;
    categoryEl.className = `modal-category ${data.category.toLowerCase()}-badge`;
    
    // Update description
    modal.querySelector('#modalDescription').textContent = data.description || 'Bu sponsor hakkında detaylı bilgi yakında eklenecek.';
    
    // Update contact links
    const websiteLink = modal.querySelector('#modalWebsite');
    const emailLink = modal.querySelector('#modalEmail');
    const phoneLink = modal.querySelector('#modalPhone');
    
    if (data.website) {
        websiteLink.href = data.website;
        websiteLink.style.display = 'flex';
    } else {
        websiteLink.style.display = 'none';
    }
    
    if (data.email) {
        emailLink.href = `mailto:${data.email}`;
        emailLink.style.display = 'flex';
    } else {
        emailLink.style.display = 'none';
    }
    
    if (data.phone) {
        phoneLink.href = `tel:${data.phone}`;
        phoneLink.style.display = 'flex';
    } else {
        phoneLink.style.display = 'none';
    }
    
    // Update social links
    const instagramLink = modal.querySelector('#modalInstagram');
    const facebookLink = modal.querySelector('#modalFacebook');
    const linkedinLink = modal.querySelector('#modalLinkedin');
    
    if (data.instagram) {
        instagramLink.href = data.instagram;
        instagramLink.style.display = 'flex';
    } else {
        instagramLink.style.display = 'none';
    }
    
    if (data.facebook) {
        facebookLink.href = data.facebook;
        facebookLink.style.display = 'flex';
    } else {
        facebookLink.style.display = 'none';
    }
    
    if (data.linkedin) {
        linkedinLink.href = data.linkedin;
        linkedinLink.style.display = 'flex';
    } else {
        linkedinLink.style.display = 'none';
    }
    
    // Update partnership period
    modal.querySelector('#modalPartnership').textContent = data.partnershipPeriod;
}

// Mock data function (replace with real API call)
function getMockSponsorData(sponsorId) {
    const mockData = {
        '1': {
            name: '156',
            logo: null,
            category: 'Platinum',
            description: 'Premium otomobil markası',
            website: 'https://156.az',
            email: 'info@156.az',
            phone: '+994501234567',
            instagram: 'https://instagram.com/156',
            facebook: 'https://facebook.com/156',
            linkedin: 'https://linkedin.com/company/156',
            partnershipPeriod: '2023 - 2025'
        },
        '2': {
            name: 'Bizon',
            logo: null,
            category: 'Gold',
            description: 'Güvenilir otomobil servisi',
            website: 'https://bizon.az',
            email: 'info@bizon.az',
            phone: '+994502345678',
            instagram: 'https://instagram.com/bizon',
            facebook: 'https://facebook.com/bizon',
            linkedin: null,
            partnershipPeriod: '2024 - 2025'
        }
    };
    
    return mockData[sponsorId] || {
        name: 'Sponsor',
        logo: null,
        category: 'Bronze',
        description: 'Bu sponsor hakkında detaylı bilgi yakında eklenecek.',
        website: null,
        email: null,
        phone: null,
        instagram: null,
        facebook: null,
        linkedin: null,
        partnershipPeriod: '2025 - Devam Ediyor'
    };
}

// Sound effects
function playHoverSound() {
    // Create audio context for hover sound
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    
    // Simple hover sound
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
    oscillator.frequency.exponentialRampToValueAtTime(600, audioContext.currentTime + 0.1);
    
    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.1);
}

// Particle animation enhancement
function initParticles() {
    const particlesContainer = document.querySelector('.particles');
    if (!particlesContainer) return;
    
    // Create additional particles
    for (let i = 0; i < 20; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.cssText = `
            position: absolute;
            width: 1px;
            height: 1px;
            background: #00D4FF;
            border-radius: 50%;
            left: ${Math.random() * 100}%;
            animation: particleFloat ${6 + Math.random() * 4}s linear infinite;
            animation-delay: ${Math.random() * 6}s;
        `;
        particlesContainer.appendChild(particle);
    }
}

// Initialize particles when page loads
document.addEventListener('DOMContentLoaded', function() {
    initParticles();
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add loading animation for images
document.querySelectorAll('img').forEach(img => {
    img.addEventListener('load', function() {
        this.style.opacity = '1';
    });
    
    img.addEventListener('error', function() {
        this.style.display = 'none';
    });
});

// Add intersection observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
        }
    });
}, observerOptions);

// Observe elements for animation
document.querySelectorAll('.sponsor-card, .contact-item').forEach(el => {
    observer.observe(el);
}); 