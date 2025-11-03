/**
 * Navigation JavaScript
 * Handles mobile menu toggle and dropdown menus
 */

document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    // Mobile menu toggle
    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = mobileMenu.style.display === 'block';
            mobileMenu.style.display = isOpen ? 'none' : 'block';
            mobileMenuToggle.setAttribute('aria-expanded', String(!isOpen));
            mobileMenu.setAttribute('aria-hidden', String(isOpen));
        });

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.style.display === 'block') {
                mobileMenu.style.display = 'none';
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
            }
        });

        // Hide menu on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024 && mobileMenu.style.display === 'block') {
                mobileMenu.style.display = 'none';
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
            }
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (mobileMenu && mobileMenuToggle) {
            const isClickInside = mobileMenu.contains(event.target) || mobileMenuToggle.contains(event.target);
            if (!isClickInside && mobileMenu.style.display === 'block') {
                mobileMenu.style.display = 'none';
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
            }
        }
    });

    // Mobile submenu toggle
    const mobileMenuLinks = mobileMenu ? mobileMenu.querySelectorAll('.menu-item-has-children > a') : [];
    mobileMenuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const parent = this.parentElement;
            const submenu = parent.querySelector('.sub-menu');
            if (submenu) {
                e.preventDefault();
                const open = submenu.style.display === 'block';
                // Close other submenus
                mobileMenu.querySelectorAll('.sub-menu').forEach(sm => {
                    if (sm !== submenu) sm.style.display = 'none';
                });
                submenu.style.display = open ? 'none' : 'block';
                parent.setAttribute('aria-expanded', String(!open));
            }
        });
    });

    // Smooth scroll for on-page anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return; // Skip empty anchor
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const offsetTop = target.getBoundingClientRect().top + window.pageYOffset - 80; // Fixed header height
                window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                if (mobileMenu && mobileMenu.style.display === 'block') {
                    mobileMenu.style.display = 'none';
                    mobileMenuToggle?.setAttribute('aria-expanded', 'false');
                    mobileMenu.setAttribute('aria-hidden', 'true');
                }
            }
        });
    });
});
