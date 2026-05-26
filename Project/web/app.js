/* ============================================
   app.js — Aurielle client-side interactivity
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {

    // ── Sticky header shadow ──────────────────────────
    const header = document.getElementById('siteHeader');
    if (header) {
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 10);
        });
    }

    // ── Mobile hamburger ─────────────────────────────
    const hamburger = document.getElementById('hamburger');
    const mainNav   = document.getElementById('mainNav');
    const overlay   = document.getElementById('navOverlay');
    if (hamburger && mainNav) {
        const openNav = () => {
            hamburger.classList.add('open');
            mainNav.classList.add('open');
            overlay.classList.add('open');
            hamburger.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        };
        const closeNav = () => {
            hamburger.classList.remove('open');
            mainNav.classList.remove('open');
            overlay.classList.remove('open');
            hamburger.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        };
        hamburger.addEventListener('click', () => mainNav.classList.contains('open') ? closeNav() : openNav());
        overlay.addEventListener('click', closeNav);
    }

    // ── User dropdown ─────────────────────────────────
    const dropdownBtn  = document.getElementById('userDropdownBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = dropdownMenu.classList.toggle('open');
            dropdownBtn.setAttribute('aria-expanded', isOpen);
        });
        document.addEventListener('click', () => {
            dropdownMenu.classList.remove('open');
            dropdownBtn?.setAttribute('aria-expanded', 'false');
        });
    }

    // ── Toast notifications ───────────────────────────
    window.showToast = function(message, type = 'default') {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        const icons = { success: '✓', error: '✕', default: '✦' };
        toast.innerHTML = `<span>${icons[type] || '✦'}</span><span>${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('toast-out');
            toast.addEventListener('animationend', () => toast.remove());
        }, 3500);
    };

    // Show flash toast if PHP set one
    const flashToast = document.getElementById('flashToast');
    if (flashToast) {
        showToast(flashToast.dataset.message, flashToast.dataset.type || 'default');
    }

    // ── Qty controls (cart page) ──────────────────────
    document.querySelectorAll('.qty-control').forEach(ctrl => {
        const dec     = ctrl.querySelector('.qty-dec');
        const inc     = ctrl.querySelector('.qty-inc');
        const val     = ctrl.querySelector('.qty-value');
        const form    = ctrl.closest('form');
        const hiddenInput = ctrl.querySelector('input[type="hidden"]');
        if (!dec || !inc || !val) return;

        dec.addEventListener('click', () => {
            const current = parseInt(val.textContent);
            if (current <= 1) return;
            val.textContent = current - 1;
            if (hiddenInput) hiddenInput.value = current - 1;
            if (form) debounceSubmit(form);
        });
        inc.addEventListener('click', () => {
            const current = parseInt(val.textContent);
            val.textContent = current + 1;
            if (hiddenInput) hiddenInput.value = current + 1;
            if (form) debounceSubmit(form);
        });
    });

    function debounceSubmit(form) {
        clearTimeout(form._timer);
        form._timer = setTimeout(() => form.submit(), 600);
    }

    // ── Wishlist toggle ───────────────────────────────
    document.querySelectorAll('.wishlist-btn[data-product-id]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const id = btn.dataset.productId;
            try {
                const res  = await fetch(`cart-actions.php?action=wishlist&id=${id}`);
                const data = await res.json();
                if (data.loggedOut) {
                    window.location.href = 'login.php?redirect=shop.php';
                    return;
                }
                btn.classList.toggle('active', data.added);
                const badge = document.getElementById('wishlistBadge');
                if (badge) badge.textContent = data.wishlistCount;
                showToast(data.added ? 'Added to wishlist ♡' : 'Removed from wishlist', data.added ? 'success' : 'default');
            } catch {
                showToast('Something went wrong', 'error');
            }
        });
    });

    // ── Add to bag (non-cart pages) ───────────────────
    document.querySelectorAll('.add-to-bag-btn[data-product-id]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const id  = btn.dataset.productId;
            const qty = document.getElementById('productQty')?.value || 1;
            const original = btn.textContent;
            btn.textContent = 'Adding…';
            btn.disabled = true;
            try {
                const res  = await fetch(`cart-actions.php?action=add&id=${id}&qty=${qty}`);
                const data = await res.json();
                const badge = document.getElementById('cartBadge');
                if (badge) {
                    badge.textContent = data.cartCount;
                    badge.style.display = 'flex';
                }
                showToast('Added to your bag ✦', 'success');
            } catch {
                showToast('Could not add to bag', 'error');
            } finally {
                btn.textContent = original;
                btn.disabled = false;
            }
        });
    });

    // ── FAQ accordion ─────────────────────────────────
    document.querySelectorAll('.faq-question').forEach(q => {
        q.addEventListener('click', () => {
            const item = q.closest('.faq-item');
            const isOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
            if (!isOpen) item.classList.add('open');
        });
    });

    // ── Newsletter form ───────────────────────────────
    document.querySelectorAll('.newsletter-form, #footerNewsletter').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const input = form.querySelector('input[type="email"]');
            showToast('Thank you for subscribing! ✦', 'success');
            if (input) input.value = '';
        });
    });

    // ── Form validation ───────────────────────────────
    document.querySelectorAll('form.validate').forEach(form => {
        form.addEventListener('submit', (e) => {
            let valid = true;
            form.querySelectorAll('[required]').forEach(field => {
                const err = field.parentElement.querySelector('.form-error-msg');
                if (!field.value.trim()) {
                    field.classList.add('error');
                    if (err) err.textContent = 'This field is required.';
                    valid = false;
                } else {
                    field.classList.remove('error');
                    if (err) err.textContent = '';
                }
                if (field.type === 'email' && field.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)) {
                    field.classList.add('error');
                    if (err) err.textContent = 'Please enter a valid email.';
                    valid = false;
                }
            });
            if (!valid) e.preventDefault();
        });
        form.querySelectorAll('[required]').forEach(field => {
            field.addEventListener('input', () => {
                field.classList.remove('error');
                const err = field.parentElement.querySelector('.form-error-msg');
                if (err) err.textContent = '';
            });
        });
    });

    // ── handleNewsletter global (footer) ─────────────
    window.handleNewsletter = function(e) {
        e.preventDefault();
        showToast('Thank you for subscribing! ✦', 'success');
        e.target.querySelector('input').value = '';
    };

});
