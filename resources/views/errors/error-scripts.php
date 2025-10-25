// Theme Toggle Functionality
const themeToggle = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');
const body = document.body;

// Load saved theme or detect system preference
const savedTheme = localStorage.getItem('theme');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
    body.setAttribute('data-theme', 'dark');
    themeIcon.textContent = '☀️';
}

themeToggle.addEventListener('click', () => {
    const currentTheme = body.getAttribute('data-theme');
    
    if (currentTheme === 'dark') {
        body.removeAttribute('data-theme');
        themeIcon.textContent = '🌙';
        localStorage.setItem('theme', 'light');
    } else {
        body.setAttribute('data-theme', 'dark');
        themeIcon.textContent = '☀️';
        localStorage.setItem('theme', 'dark');
    }
});

// Button loading states
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.href && !this.classList.contains('loading')) {
                this.classList.add('loading');
                setTimeout(() => {
                    this.classList.remove('loading');
                }, 2000);
            }
        });
        
        button.addEventListener('mousedown', function() {
            this.style.transform = 'scale(0.95)';
        });
        
        button.addEventListener('mouseup', function() {
            this.style.transform = '';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Alt + H for home
    if (e.altKey && e.key === 'h') {
        e.preventDefault();
        const homeBtn = document.querySelector('.btn-primary[href="/"]');
        if (homeBtn) homeBtn.click();
    }
    
    // Escape key to go back
    if (e.key === 'Escape') {
        const backBtn = document.querySelector('button[onclick="history.back()"]');
        if (backBtn && !backBtn.disabled) backBtn.click();
    }
});

// Console message for developers
console.log(
    '%c⚡ Haryadi Framework - Error Page',
    'color: #6366f1; font-size: 16px; font-weight: bold;'
);