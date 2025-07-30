// Registration Page JavaScript

const registrationForm = document.getElementById('registrationForm');
const submitBtn = document.querySelector('.submit-btn');
const btnText = document.querySelector('.btn-text');
const btnLoader = document.querySelector('.btn-loader');

// Photo upload functionality
const photoInputs = document.querySelectorAll('.photo-input');
const photoPreviews = document.querySelectorAll('.photo-preview');

// Form validation rules
const validationRules = {
    firstName: {
        required: true,
        minLength: 2,
        pattern: /^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/,
        message: 'Ad en az 2 karakter olmalı ve sadece harf içermeli'
    },
    lastName: {
        required: true,
        minLength: 2,
        pattern: /^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/,
        message: 'Soyad en az 2 karakter olmalı ve sadece harf içermeli'
    },
    email: {
        required: true,
        pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        message: 'Geçerli bir e-posta adresi giriniz'
    },
    phone: {
        required: true,
        pattern: /^[0-9\s\-\+\(\)]+$/,
        minLength: 10,
        message: 'Geçerli bir telefon numarası giriniz'
    },
    username: {
        required: true,
        minLength: 3,
        pattern: /^[a-zA-Z0-9_]+$/,
        message: 'Kullanıcı adı en az 3 karakter olmalı ve sadece harf, rakam, _ içermeli'
    },
    carBrand: {
        required: true,
        message: 'Araç markası seçiniz'
    },
    carModel: {
        required: true,
        minLength: 2,
        message: 'Araç modeli giriniz'
    },
    carYear: {
        required: true,
        message: 'Model yılı seçiniz'
    },
    carColor: {
        required: true,
        minLength: 2,
        message: 'Araç rengi giriniz'
    },
    experience: {
        required: true,
        message: 'Deneyim seviyesi seçiniz'
    },
    agreement: {
        required: true,
        message: 'Kullanım şartlarını kabul etmelisiniz'
    }
};

// Photo upload event listeners
photoInputs.forEach((input, index) => {
    input.addEventListener('change', (e) => handlePhotoUpload(e, index));
});

// Handle photo upload
function handlePhotoUpload(event, index) {
    const file = event.target.files[0];
    const preview = photoPreviews[index];
    
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            showNotification('Yalnız şəkil faylları yükləyə bilərsiniz', 'error');
            return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showNotification('Fayl ölçüsü 5MB-dan çox ola bilməz', 'error');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            // Clear existing content
            preview.innerHTML = '';
            
            // Create image element
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'Uploaded photo';
            
            // Create remove button
            const removeBtn = document.createElement('button');
            removeBtn.className = 'remove-photo';
            removeBtn.innerHTML = '×';
            removeBtn.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                removePhoto(index);
            };
            
            // Add elements to preview
            preview.appendChild(img);
            preview.appendChild(removeBtn);
            preview.classList.add('has-image');
        };
        reader.readAsDataURL(file);
    }
}

// Remove photo
function removePhoto(index) {
    const input = photoInputs[index];
    const preview = photoPreviews[index];
    
    // Clear input
    input.value = '';
    
    // Reset preview
    preview.innerHTML = `
        <div class="upload-placeholder">
            <span class="upload-icon">📷</span>
            <span class="upload-text">${getUploadText(index)}</span>
        </div>
    `;
    preview.classList.remove('has-image');
}

// Get upload text based on index
function getUploadText(index) {
    const texts = ['Ön Görünüş', 'Arxa Görünüş', 'Sol Yan', 'Sağ Yan', 'İç Görünüş', 'Mühərrik'];
    return texts[index] || 'Fotoşəkil';
}

// Real-time validation
Object.keys(validationRules).forEach(fieldName => {
    const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
    if (field) {
        field.addEventListener('blur', () => validateField(fieldName));
        field.addEventListener('input', () => clearFieldError(fieldName));
    }
});

// Form submission
registrationForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    if (validateForm()) {
        await submitForm();
    }
});

// Add CSRF token to all AJAX requests
function getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

// Validate individual field
function validateField(fieldName) {
    const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
    const rules = validationRules[fieldName];
    const value = field.type === 'checkbox' ? field.checked : field.value.trim();
    
    clearFieldError(fieldName);
    
    // Required check
    if (rules.required && (!value || value === '')) {
        showFieldError(fieldName, rules.message);
        return false;
    }
    
    // Pattern check
    if (value && rules.pattern && !rules.pattern.test(value)) {
        showFieldError(fieldName, rules.message);
        return false;
    }
    
    // Min length check
    if (value && rules.minLength && value.length < rules.minLength) {
        showFieldError(fieldName, rules.message);
        return false;
    }
    
    // Show success
    showFieldSuccess(fieldName);
    return true;
}

// Validate entire form
function validateForm() {
    let isValid = true;
    
    Object.keys(validationRules).forEach(fieldName => {
        if (!validateField(fieldName)) {
            isValid = false;
        }
    });
    
    // Check interests (optional - no validation needed)
    // const interests = document.querySelectorAll('[name="interests[]"]:checked');
    // if (interests.length === 0) {
    //     showNotification('En az bir ilgi alanı seçmelisiniz', 'error');
    //     isValid = false;
    // }
    
    return isValid;
}

// Show field error
function showFieldError(fieldName, message) {
    const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
    const formGroup = field.closest('.form-group');
    
    // Remove existing error
    const existingError = formGroup.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Add error class
    formGroup.classList.add('error');
    field.style.borderColor = '#FF1493';
    
    // Create error message
    const errorElement = document.createElement('div');
    errorElement.className = 'field-error';
    errorElement.textContent = message;
    errorElement.style.cssText = `
        color: #FF1493;
        font-size: 0.8rem;
        margin-top: 0.3rem;
        animation: shake 0.3s ease;
    `;
    
    formGroup.appendChild(errorElement);
    
    // Play error sound
    playErrorSound();
}

// Clear field error
function clearFieldError(fieldName) {
    const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
    const formGroup = field.closest('.form-group');
    
    formGroup.classList.remove('error', 'success');
    field.style.borderColor = '';
    
    const errorElement = formGroup.querySelector('.field-error');
    if (errorElement) {
        errorElement.remove();
    }
}

// Show field success
function showFieldSuccess(fieldName) {
    const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
    const formGroup = field.closest('.form-group');
    
    formGroup.classList.add('success');
    field.style.borderColor = '#00FFFF';
    
    // Play success sound
    playSuccessSound();
}

// Submit form
async function submitForm() {
    try {
        // Show loading state
        submitBtn.disabled = true;
        btnText.style.opacity = '0';
        btnLoader.style.display = 'block';
        
        // Create FormData object
        const formData = new FormData(registrationForm);
        
        // Send form data to server
        const response = await fetch('/register', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken(),
                'Accept': 'application/json',
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showNotification(result.message, 'success');
            playSuccessSound();

            // Reset form
            registrationForm.reset();
            
            // Reset photo previews
            photoPreviews.forEach((preview, index) => {
                removePhoto(index);
            });
        } else {
            showNotification(result.message || 'Xəta baş verdi! Zəhmət olmasa yenidən cəhd edin.', 'error');
            playErrorSound();
        }

    } catch (error) {
        console.error('Error:', error);
        showNotification('Xəta baş verdi! Zəhmət olmasa yenidən cəhd edin.', 'error');
        playErrorSound();
    } finally {
        // Reset button state
        submitBtn.disabled = false;
        btnText.style.opacity = '1';
        btnLoader.style.display = 'none';
    }
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
        background: ${type === 'success' ? 'var(--gradient-secondary)' : type === 'error' ? 'var(--gradient-primary)' : 'rgba(255, 255, 255, 0.1)'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 5px;
        border: 1px solid ${type === 'success' ? 'var(--secondary-color)' : type === 'error' ? 'var(--primary-color)' : 'rgba(255, 255, 255, 0.3)'};
        box-shadow: ${type === 'success' ? 'var(--shadow-cyan)' : type === 'error' ? 'var(--shadow-neon)' : '0 0 10px rgba(255, 255, 255, 0.3)'};
        z-index: 10000;
        animation: slideIn 0.3s ease;
        max-width: 300px;
        font-family: 'Exo 2', sans-serif;
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
    
    // Play notification sound
    if (type === 'success') {
        playSuccessSound();
    } else if (type === 'error') {
        playErrorSound();
    }
}

// Add notification animations
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    @keyframes slideIn {
        0% { transform: translateX(100%); opacity: 0; }
        100% { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        0% { transform: translateX(0); opacity: 1; }
        100% { transform: translateX(100%); opacity: 0; }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .form-group.success input,
    .form-group.success select,
    .form-group.success textarea {
        border-color: var(--secondary-color) !important;
        box-shadow: 0 0 10px rgba(0, 255, 255, 0.3) !important;
    }
    
    .form-group.error input,
    .form-group.error select,
    .form-group.error textarea {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 10px rgba(255, 0, 255, 0.3) !important;
    }
`;
document.head.appendChild(notificationStyles);

// Sound effects
function playSuccessSound() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        
        // Resume AudioContext if suspended
        if (audioContext.state === 'suspended') {
            audioContext.resume();
        }
        
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(523, audioContext.currentTime); // C5
        oscillator.frequency.setValueAtTime(659, audioContext.currentTime + 0.1); // E5
        oscillator.frequency.setValueAtTime(784, audioContext.currentTime + 0.2); // G5
        
        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.3);
    } catch (error) {
        console.log('Audio playback failed:', error.message);
    }
}

function playErrorSound() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        
        // Resume AudioContext if suspended
        if (audioContext.state === 'suspended') {
            audioContext.resume();
        }
        
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(300, audioContext.currentTime);
        oscillator.frequency.setValueAtTime(200, audioContext.currentTime + 0.1);
        
        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.2);
    } catch (error) {
        console.log('Audio playback failed:', error.message);
    }
}

// Enhanced form interactions
document.querySelectorAll('.form-group input, .form-group select, .form-group textarea').forEach(field => {
    // Focus effects
    field.addEventListener('focus', () => {
        const formGroup = field.closest('.form-group');
        formGroup.style.transform = 'scale(1.02)';
        
        // Add glow effect
        const glow = formGroup.querySelector('.form-glow');
        if (glow) {
            glow.style.width = '100%';
        }
    });
    
    field.addEventListener('blur', () => {
        const formGroup = field.closest('.form-group');
        formGroup.style.transform = 'scale(1)';
        
        // Remove glow effect
        const glow = formGroup.querySelector('.form-glow');
        if (glow && !field.value) {
            glow.style.width = '0';
        }
    });
});

// Checkbox animations
document.querySelectorAll('.checkbox-label').forEach(label => {
    label.addEventListener('click', () => {
        const checkbox = label.querySelector('input[type="checkbox"]');
        const checkmark = label.querySelector('.checkmark');
        
        if (checkbox.checked) {
            checkmark.style.animation = 'checkPop 0.3s ease';
        }
    });
});

// Add checkbox animation
const checkboxStyles = document.createElement('style');
checkboxStyles.textContent = `
    @keyframes checkPop {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(checkboxStyles);

// Character counter for username
const usernameField = document.getElementById('username');
if (usernameField) {
    const counter = document.createElement('div');
    counter.className = 'char-counter';
    counter.style.cssText = `
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.8rem;
        color: var(--text-gray);
        font-family: 'Orbitron', monospace;
    `;
    
    usernameField.parentNode.style.position = 'relative';
    usernameField.parentNode.appendChild(counter);
    
    usernameField.addEventListener('input', () => {
        const length = usernameField.value.length;
        counter.textContent = `${length}/20`;
        
        if (length >= 15) {
            counter.style.color = 'var(--primary-color)';
        } else {
            counter.style.color = 'var(--text-gray)';
        }
    });
}

// Initialize page
document.addEventListener('DOMContentLoaded', () => {
    // Add loading animation to form
    const form = document.querySelector('.registration-form');
    if (form) {
        form.style.opacity = '0';
        form.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            form.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            form.style.opacity = '1';
            form.style.transform = 'translateY(0)';
        }, 200);
    }
    
    // Focus first field
    const firstField = document.getElementById('firstName');
    if (firstField) {
        setTimeout(() => firstField.focus(), 500);
    }
}); 