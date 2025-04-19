// Mobile submenu toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const mobileNav = document.querySelector('.mobile-nav');
    const mobileNavClose = document.querySelector('.mobile-nav-close');
    const overlay = document.createElement('div');
    overlay.className = 'mobile-nav-overlay';
    document.body.appendChild(overlay);

    if (mobileToggle && mobileNav) {
        mobileToggle.addEventListener('click', function(e) {
            e.preventDefault();
            mobileNav.classList.add('active');
            document.body.style.overflow = 'hidden';
            overlay.style.display = 'block';
        });
    }

    if (mobileNavClose && mobileNav) {
        mobileNavClose.addEventListener('click', function() {
            mobileNav.classList.remove('active');
            document.body.style.overflow = '';
            overlay.style.display = 'none';
        });
    }

    overlay.addEventListener('click', function() {
        mobileNav.classList.remove('active');
        document.body.style.overflow = '';
        overlay.style.display = 'none';
    });
    
    // Mobile submenu toggle
    const mobileSubmenuToggles = document.querySelectorAll('.mobile-submenu-toggle');
    
    mobileSubmenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle the active class
            this.classList.toggle('active');
            
            // Find the next sibling which should be the submenu
            const submenu = this.nextElementSibling;
            
            // Toggle submenu visibility
            if (submenu.style.maxHeight) {
                submenu.style.maxHeight = null;
                // Change the icon to down arrow
                this.querySelector('i').classList.remove('fa-chevron-up');
                this.querySelector('i').classList.add('fa-chevron-down');
            } else {
                submenu.style.maxHeight = submenu.scrollHeight + "px";
                // Change the icon to up arrow
                this.querySelector('i').classList.remove('fa-chevron-down');
                this.querySelector('i').classList.add('fa-chevron-up');
            }
        });
    });
    
    // Back to top button
    const backToTopButton = document.getElementById('backToTop');
    
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('active');
            } else {
                backToTopButton.classList.remove('active');
            }
        });
        
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}); 