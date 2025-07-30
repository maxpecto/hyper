// Sponsors Slideshow JavaScript

let currentSlideIndex = 1;
const totalSlides = 8;
let slideInterval;
let isAutoPlaying = true;

// Initialize slideshow when page loads
document.addEventListener('DOMContentLoaded', function() {
    showSlide(currentSlideIndex);
    startAutoSlide();
    addKeyboardNavigation();
    addTouchNavigation();
});

// Show specific slide
function showSlide(n) {
    let slides = document.querySelectorAll('.sponsor-slide');
    let indicators = document.querySelectorAll('.indicator');
    
    if (n > totalSlides) { currentSlideIndex = 1; }
    if (n < 1) { currentSlideIndex = totalSlides; }
    
    // Hide all slides
    slides.forEach(slide => {
        slide.classList.remove('active');
    });
    
    // Remove active from all indicators
    indicators.forEach(indicator => {
        indicator.classList.remove('active');
    });
    
    // Show current slide
    if (slides[currentSlideIndex - 1]) {
        slides[currentSlideIndex - 1].classList.add('active');
    }
    
    // Highlight current indicator
    if (indicators[currentSlideIndex - 1]) {
        indicators[currentSlideIndex - 1].classList.add('active');
    }
}

// Change slide (next/previous)
function changeSlide(n) {
    resetAutoSlide();
    currentSlideIndex += n;
    showSlide(currentSlideIndex);
}

// Go to specific slide
function currentSlide(n) {
    resetAutoSlide();
    currentSlideIndex = n;
    showSlide(currentSlideIndex);
}

// Auto slide functionality
function startAutoSlide() {
    slideInterval = setInterval(() => {
        if (isAutoPlaying) {
            currentSlideIndex++;
            showSlide(currentSlideIndex);
        }
    }, 8000); // Change slide every 8 seconds
}

function stopAutoSlide() {
    isAutoPlaying = false;
    clearInterval(slideInterval);
}

function resetAutoSlide() {
    stopAutoSlide();
    setTimeout(() => {
        isAutoPlaying = true;
        startAutoSlide();
    }, 3000); // Resume auto-play after 3 seconds of inactivity
}

// Keyboard navigation
function addKeyboardNavigation() {
    document.addEventListener('keydown', function(event) {
        switch(event.key) {
            case 'ArrowRight':
            case ' ': // Spacebar
                event.preventDefault();
                changeSlide(1);
                break;
            case 'ArrowLeft':
                event.preventDefault();
                changeSlide(-1);
                break;
            case 'Home':
                event.preventDefault();
                currentSlide(1);
                break;
            case 'End':
                event.preventDefault();
                currentSlide(totalSlides);
                break;
            case 'Escape':
                event.preventDefault();
                // Toggle auto-play
                if (isAutoPlaying) {
                    stopAutoSlide();
                } else {
                    isAutoPlaying = true;
                    startAutoSlide();
                }
                break;
        }
    });
}

// Touch/swipe navigation for mobile
function addTouchNavigation() {
    let startX = 0;
    let endX = 0;
    
    document.addEventListener('touchstart', function(event) {
        startX = event.touches[0].clientX;
    });
    
    document.addEventListener('touchend', function(event) {
        endX = event.changedTouches[0].clientX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const threshold = 50; // Minimum swipe distance
        const diff = startX - endX;
        
        if (Math.abs(diff) > threshold) {
            if (diff > 0) {
                // Swipe left - next slide
                changeSlide(1);
            } else {
                // Swipe right - previous slide
                changeSlide(-1);
            }
        }
    }
}

// Mouse wheel navigation
document.addEventListener('wheel', function(event) {
    event.preventDefault();
    
    if (event.deltaY > 0) {
        // Scroll down - next slide
        changeSlide(1);
    } else {
        // Scroll up - previous slide
        changeSlide(-1);
    }
}, { passive: false });

// Auto-hide navigation after inactivity
let navigationTimeout;
const navigation = document.querySelector('.slide-navigation');

function hideNavigation() {
    navigation.classList.add('hidden');
}

function showNavigation() {
    navigation.classList.remove('hidden');
    clearTimeout(navigationTimeout);
    navigationTimeout = setTimeout(hideNavigation, 3000);
}

// Show navigation on mouse move
document.addEventListener('mousemove', showNavigation);
document.addEventListener('touchstart', showNavigation);

// Keep navigation visible when hovering over it
navigation.addEventListener('mouseenter', () => {
    clearTimeout(navigationTimeout);
});

navigation.addEventListener('mouseleave', () => {
    navigationTimeout = setTimeout(hideNavigation, 3000);
});

// Pause auto-play when tab is not visible
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        stopAutoSlide();
    } else if (isAutoPlaying) {
        startAutoSlide();
    }
});

// Add keyboard hint to page
function addKeyboardHint() {
    const hint = document.createElement('div');
    hint.className = 'keyboard-hint';
    hint.innerHTML = `
        <p><span class="key">←/→</span> Navigate</p>
        <p><span class="key">Space</span> Next</p>
        <p><span class="key">Home/End</span> First/Last</p>
        <p><span class="key">Esc</span> Pause/Play</p>
    `;
    document.body.appendChild(hint);
    
    // Hide hint on mobile
    if (window.innerWidth <= 768) {
        hint.style.display = 'none';
    }
}

// Initialize keyboard hint
document.addEventListener('DOMContentLoaded', addKeyboardHint);

// Progress indicator (optional)
function updateProgress() {
    const progress = (currentSlideIndex / totalSlides) * 100;
    // You can add a progress bar here if needed
}

// Smooth slide transitions
function addSmoothTransitions() {
    const slides = document.querySelectorAll('.sponsor-slide');
    slides.forEach(slide => {
        slide.style.transition = 'opacity 0.5s ease-in-out';
    });
}

// Initialize smooth transitions
document.addEventListener('DOMContentLoaded', addSmoothTransitions);

// Preload images for better performance
function preloadImages() {
    for (let i = 1; i <= totalSlides; i++) {
        const img = new Image();
        img.src = `sponsors/HYPERDRİVE_PARKMEET25-${i}.png`;
    }
}

// Preload images when page loads
document.addEventListener('DOMContentLoaded', preloadImages);

// Add fullscreen functionality (optional)
function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

// Add fullscreen on F11 or double-click
document.addEventListener('keydown', function(event) {
    if (event.key === 'F11') {
        event.preventDefault();
        toggleFullscreen();
    }
});

// Double-click to toggle fullscreen
document.addEventListener('dblclick', function(event) {
    if (event.target.closest('.sponsor-slide')) {
        toggleFullscreen();
    }
});

// Handle browser resize
window.addEventListener('resize', function() {
    // Adjust layout if needed
    const slides = document.querySelectorAll('.sponsor-slide');
    slides.forEach(slide => {
        const img = slide.querySelector('img');
        if (img) {
            img.style.width = '100%';
            img.style.height = '100vh';
            img.style.objectFit = 'cover';
        }
    });
});

// Add loading animation
function showLoading() {
    const loading = document.createElement('div');
    loading.className = 'loading-spinner';
    loading.innerHTML = '<div class="spinner"></div>';
    loading.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    `;
    document.body.appendChild(loading);
    
    // Remove loading when images are loaded
    setTimeout(() => {
        loading.remove();
    }, 1000);
}

// Show loading on page load
document.addEventListener('DOMContentLoaded', showLoading);

console.log('🚗 HyperDrive Sponsors Slideshow Loaded Successfully!'); 