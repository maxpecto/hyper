// DOM Elements
const navbar = document.querySelector('.navbar');
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');
const heroVideos = document.querySelectorAll('.hero-video iframe');
const scrollIndicator = document.querySelector('.scroll-indicator');
const heroContent = document.getElementById('heroContent');
const heroToggleBtn = document.getElementById('heroToggleBtn');

// Navbar scroll effect
window.addEventListener('scroll', () => {
    if (window.scrollY > 100) {
        navbar.style.background = 'rgba(0, 0, 0, 0.7)';
        navbar.style.backdropFilter = 'blur(20px)';
    } else {
        navbar.style.background = 'rgba(0, 0, 0, 0.3)';
        navbar.style.backdropFilter = 'blur(15px)';
    }
});

// Mobile menu toggle
hamburger?.addEventListener('click', () => {
    const navMenus = document.querySelectorAll('.nav-menu');
    navMenus.forEach(menu => {
        menu.classList.toggle('active');
    });
    hamburger.classList.toggle('active');
});

// Close mobile menu when clicking on a link
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
        const navMenus = document.querySelectorAll('.nav-menu');
        navMenus.forEach(menu => {
            menu.classList.remove('active');
        });
        hamburger.classList.remove('active');
    });
});

// Hero content toggle functionality
let heroContentVisible = true;

// Auto-hide hero content after 5 seconds
setTimeout(() => {
    if (heroContentVisible) {
        hideHeroContent();
    }
}, 5000);

// Toggle button click event
if (heroToggleBtn) {
    heroToggleBtn.addEventListener('click', () => {
        if (heroContentVisible) {
            hideHeroContent();
        } else {
            showHeroContent();
        }
    });
}

function hideHeroContent() {
    if (heroContent) {
        heroContent.classList.add('hidden');
    }
    if (heroToggleBtn) {
        heroToggleBtn.classList.add('visible', 'rotated');
        heroToggleBtn.classList.remove('content-bottom');
    }
    heroContentVisible = false;
    
    // Play slide sound
    playSlideSound();
}

function showHeroContent() {
    if (heroContent) {
        heroContent.classList.remove('hidden');
    }
    if (heroToggleBtn) {
        heroToggleBtn.classList.remove('rotated');
        heroToggleBtn.classList.add('content-bottom', 'visible');
    }
    heroContentVisible = true;
    
    // Play slide sound
    playSlideSound();
    
    // Hide toggle button after showing content
    setTimeout(() => {
        if (heroContentVisible && heroToggleBtn) {
            heroToggleBtn.classList.remove('visible');
        }
    }, 3000);
}

// Scroll indicator click
scrollIndicator?.addEventListener('click', () => {
    window.scrollTo({
        top: window.innerHeight,
        behavior: 'smooth'
    });
});

// YouTube iframe autoplay fallback
if (heroVideos.length > 0) {
    heroVideos.forEach(video => {
        // YouTube iframe will handle autoplay via URL parameters
        // Add fallback for browsers that don't support autoplay
        video.addEventListener('load', () => {
            console.log('YouTube video uğurla yükləndi');
        });
        
        video.addEventListener('error', () => {
            console.log('YouTube video yüklənmədi');
            // Fallback to static background if iframe fails
            const heroSection = document.querySelector('.hero');
            heroSection.style.background = 'linear-gradient(45deg, rgba(255, 0, 255, 0.2), rgba(0, 255, 255, 0.2)), url("https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=1920&h=1080&fit=crop") center/cover';
        });
    });
}

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe all animatable elements
document.querySelectorAll('.event-card, .stat-card, .community-text, .community-stats').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});

// Glitch effect for title
const glitchText = document.querySelector('.glitch');
if (glitchText) {
    setInterval(() => {
        const shouldGlitch = Math.random() < 0.1; // 10% chance
        if (shouldGlitch) {
            glitchText.style.animation = 'none';
            setTimeout(() => {
                glitchText.style.animation = 'glitch 2s infinite';
            }, 50);
        }
    }, 3000);
}

// Parallax effect removed - video uses CSS transform only

// Mouse glow effect
document.addEventListener('mousemove', (e) => {
    const cursor = document.querySelector('.cursor-glow');
    if (!cursor) {
        const cursorElement = document.createElement('div');
        cursorElement.className = 'cursor-glow';
        cursorElement.style.cssText = `
            position: fixed;
            width: 20px;
            height: 20px;
            background: radial-gradient(circle, rgba(255, 0, 255, 0.3) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            mix-blend-mode: screen;
            transition: transform 0.1s ease;
        `;
        document.body.appendChild(cursorElement);
    }
    
    const cursorGlow = document.querySelector('.cursor-glow');
    cursorGlow.style.left = e.clientX - 10 + 'px';
    cursorGlow.style.top = e.clientY - 10 + 'px';
});

// Hover effects for buttons and cards
document.querySelectorAll('.btn-primary, .btn-secondary, .event-card, .stat-card').forEach(element => {
    element.addEventListener('mouseenter', () => {
        element.style.transform = 'translateY(-5px) scale(1.02)';
    });
    
    element.addEventListener('mouseleave', () => {
        element.style.transform = 'translateY(0) scale(1)';
    });
});

// Counter animation for stats
const animateCounters = () => {
    const counters = document.querySelectorAll('.stat-number');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace('+', ''));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                counter.textContent = target + '+';
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current) + '+';
            }
        }, 16);
    });
};

// Trigger counter animation when stats come into view
const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
            entry.target.classList.add('animated');
            setTimeout(animateCounters, 300);
        }
    });
}, { threshold: 0.5 });

const heroStats = document.querySelector('.hero-stats');
if (heroStats) {
    statsObserver.observe(heroStats);
}

// Add cyber grid background effect
const createCyberGrid = () => {
    const grid = document.createElement('div');
    grid.className = 'cyber-grid';
    grid.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            linear-gradient(rgba(255, 0, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 0, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
        z-index: -1;
        animation: gridMove 20s linear infinite;
    `;
    
    // Add animation keyframes
    if (!document.querySelector('#gridAnimation')) {
        const style = document.createElement('style');
        style.id = 'gridAnimation';
        style.textContent = `
            @keyframes gridMove {
                0% { transform: translate(0, 0); }
                100% { transform: translate(50px, 50px); }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(grid);
};

// Initialize cyber grid
createCyberGrid();

// Performance optimization: Debounce scroll events
let ticking = false;
const debounceScroll = () => {
    if (!ticking) {
        requestAnimationFrame(() => {
            // Scroll-based animations here
            ticking = false;
        });
        ticking = true;
    }
};

window.addEventListener('scroll', debounceScroll, { passive: true });

// Initialize page
document.addEventListener('DOMContentLoaded', () => {
    // Add loading animation
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease';
        document.body.style.opacity = '1';
    }, 100);
    
    // Preload critical images
    const criticalImages = [
        'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=400&h=300&fit=crop',
        'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=400&h=300&fit=crop',
        'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=400&h=300&fit=crop'
    ];
    
    criticalImages.forEach(src => {
        const img = new Image();
        img.src = src;
    });
});

// Add some cyberpunk sound effects (optional)
const playClickSound = () => {
    // Web Audio API for cyber sounds
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
    oscillator.frequency.exponentialRampToValueAtTime(400, audioContext.currentTime + 0.1);
    
    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.1);
};

// Add click sounds to buttons
document.querySelectorAll('.btn-primary, .btn-secondary, .nav-link').forEach(element => {
    element.addEventListener('click', playClickSound);
});

// Slide sound effect for hero toggle
function playSlideSound() {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.setValueAtTime(400, audioContext.currentTime);
    oscillator.frequency.exponentialRampToValueAtTime(800, audioContext.currentTime + 0.3);
    
    gainNode.gain.setValueAtTime(0.08, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.3);
} 