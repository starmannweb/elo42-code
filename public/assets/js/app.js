/**
 * Elo 42 Platform — Main JS
 */
(function() {
    'use strict';

    // --- Global namespace ---
    window.Elo42 = window.Elo42 || {};

    // =========================================
    // Toast Notification System
    // =========================================
    window.Elo42.toast = function(message, type, duration) {
        type = type || 'info';
        duration = duration || 5000;

        var container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            container.setAttribute('role', 'status');
            container.setAttribute('aria-live', 'polite');
            document.body.appendChild(container);
        }

        var toast = document.createElement('div');
        toast.className = 'toast toast--' + type;
        toast.innerHTML = '<span>' + message + '</span><button class="toast__close" aria-label="Fechar">&times;</button>';

        container.appendChild(toast);

        var closeBtn = toast.querySelector('.toast__close');
        var dismiss = function() {
            toast.classList.add('toast--exit');
            setTimeout(function() { toast.remove(); }, 250);
        };

        closeBtn.addEventListener('click', dismiss);
        setTimeout(dismiss, duration);
    };

    // =========================================
    // Confirm Modal System
    // =========================================
    window.Elo42.confirm = function(options) {
        var title = options.title || 'Confirmar ação';
        var text = options.text || 'Tem certeza que deseja continuar?';
        var confirmLabel = options.confirmLabel || 'Confirmar';
        var cancelLabel = options.cancelLabel || 'Cancelar';
        var danger = options.danger !== false;
        var onConfirm = options.onConfirm;
        var iconSvg = danger
            ? '<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2 2 20h20Zm0 6.2a1 1 0 0 1 1 1v4.8a1 1 0 1 1-2 0V9.2a1 1 0 0 1 1-1Zm0 10.4a1.2 1.2 0 1 1 0-2.4 1.2 1.2 0 0 1 0 2.4Z"/></svg>'
            : '<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11 10h2v7h-2zm0-3h2v2h-2z"/><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Z"/></svg>';

        var modal = document.createElement('div');
        modal.className = 'confirm-modal active';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.innerHTML =
            '<div class="confirm-modal__card">' +
                '<div class="confirm-modal__icon">' + iconSvg + '</div>' +
                '<h3 class="confirm-modal__title">' + title + '</h3>' +
                '<p class="confirm-modal__text">' + text + '</p>' +
                '<div class="confirm-modal__actions">' +
                    '<button class="btn btn--ghost" data-action="cancel">' + cancelLabel + '</button>' +
                    '<button class="btn ' + (danger ? 'btn--danger' : 'btn--primary') + '" data-action="confirm">' + confirmLabel + '</button>' +
                '</div>' +
            '</div>';

        document.body.appendChild(modal);

        var close = function() { modal.remove(); };

        modal.querySelector('[data-action="cancel"]').addEventListener('click', close);
        modal.querySelector('[data-action="confirm"]').addEventListener('click', function() {
            close();
            if (onConfirm) onConfirm();
        });
        modal.addEventListener('click', function(e) {
            if (e.target === modal) close();
        });
        document.addEventListener('keydown', function handler(e) {
            if (e.key === 'Escape') { close(); document.removeEventListener('keydown', handler); }
        });

        modal.querySelector('[data-action="cancel"]').focus();
    };

    // =========================================
    // DOMContentLoaded
    // =========================================
    document.addEventListener('DOMContentLoaded', function() {

        // --- Header behavior (keep full style on scroll) ---
        var header = document.querySelector('.site-header');
        if (header) {
            header.classList.remove('site-header--scrolled');
        }

        // --- Mobile menu toggle ---
        var toggle = document.querySelector('.navbar__toggle');
        var menu = document.querySelector('.navbar__menu');
        var actions = document.querySelector('.navbar__actions');

        if (toggle) {
            toggle.addEventListener('click', function() {
                var isOpen = toggle.classList.toggle('active');
                menu && menu.classList.toggle('open');
                actions && actions.classList.toggle('open');
                toggle.setAttribute('aria-expanded', isOpen);
            });

            document.querySelectorAll('.navbar__link').forEach(function(link) {
                link.addEventListener('click', function() {
                    toggle.classList.remove('active');
                    menu && menu.classList.remove('open');
                    actions && actions.classList.remove('open');
                    toggle.setAttribute('aria-expanded', 'false');
                });
            });
        }

        // --- FAQ Accordion ---
        document.querySelectorAll('.faq-item__question').forEach(function(btn) {
            btn.setAttribute('role', 'button');
            btn.setAttribute('aria-expanded', 'false');

            btn.addEventListener('click', function() {
                var item = btn.closest('.faq-item');
                var isActive = item.classList.contains('active');

                document.querySelectorAll('.faq-item').forEach(function(i) {
                    i.classList.remove('active');
                    var q = i.querySelector('.faq-item__question');
                    if (q) q.setAttribute('aria-expanded', 'false');
                });

                if (!isActive) {
                    item.classList.add('active');
                    btn.setAttribute('aria-expanded', 'true');
                }
            });
        });

        // --- Steps Tabs ---
        document.querySelectorAll('.steps__tab').forEach(function(tab) {
            tab.setAttribute('role', 'tab');
            tab.addEventListener('click', function() {
                var target = tab.dataset.target;
                var container = tab.closest('.steps');

                container.querySelectorAll('.steps__tab').forEach(function(t) {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                container.querySelectorAll('.steps__content').forEach(function(c) {
                    c.classList.remove('active');
                });

                tab.classList.add('active');
                tab.setAttribute('aria-selected', 'true');
                var panel = container.querySelector('#' + target);
                if (panel) panel.classList.add('active');
            });
        });

        // --- Alert auto-dismiss ---
        document.querySelectorAll('.alert').forEach(function(alert) {
            var close = document.createElement('button');
            close.className = 'alert__close';
            close.setAttribute('aria-label', 'Fechar');
            close.innerHTML = '&times;';
            alert.appendChild(close);

            close.addEventListener('click', function() {
                alert.classList.add('alert--dismissing');
                setTimeout(function() { alert.remove(); }, 300);
            });

            setTimeout(function() {
                if (alert.parentNode) {
                    alert.classList.add('alert--dismissing');
                    setTimeout(function() { alert.remove(); }, 300);
                }
            }, 6000);
        });

        // --- Sidebar toggle with overlay ---
        var sidebarToggle = document.getElementById('hub-sidebar-toggle');
        var sidebar = document.getElementById('hub-sidebar');
        var overlay = document.querySelector('.hub-sidebar-overlay');
        var themeToggle = document.getElementById('hub-theme-toggle');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                var isOpen = sidebar.classList.toggle('open');
                if (overlay) overlay.classList.toggle('active', isOpen);
                sidebarToggle.setAttribute('aria-expanded', isOpen);
                document.body.style.overflow = isOpen ? 'hidden' : '';
            });

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                    sidebarToggle.setAttribute('aria-expanded', 'false');
                    document.body.style.overflow = '';
                });
            }
        }

        // --- Hub theme toggle (dark/light) ---
        if (themeToggle) {
            var storageKey = 'elo42_hub_theme';
            var applyTheme = function(theme) {
                var normalized = theme === 'light' ? 'light' : 'dark';
                document.body.setAttribute('data-hub-theme', normalized);
                themeToggle.textContent = normalized === 'dark' ? 'Modo claro' : 'Modo escuro';
                themeToggle.setAttribute('aria-pressed', normalized === 'dark' ? 'true' : 'false');
            };

            try {
                var savedTheme = localStorage.getItem(storageKey);
                applyTheme(savedTheme === 'light' ? 'light' : 'dark');
            } catch (e) {
                applyTheme('dark');
            }

            themeToggle.addEventListener('click', function() {
                var current = document.body.getAttribute('data-hub-theme') === 'light' ? 'light' : 'dark';
                var next = current === 'dark' ? 'light' : 'dark';
                applyTheme(next);

                try {
                    localStorage.setItem(storageKey, next);
                } catch (e) {
                    // ignore localStorage failures
                }
            });
        }

        // --- Form loading state ---
        document.querySelectorAll('form[data-loading]').forEach(function(form) {
            form.addEventListener('submit', function() {
                var btn = form.querySelector('button[type="submit"], .btn--primary');
                if (btn && !btn.classList.contains('btn--loading')) {
                    btn.classList.add('btn--loading');
                    btn.disabled = true;
                }
            });
        });

        // --- Scroll Animations ---
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });

            document.querySelectorAll('.animate-on-scroll').forEach(function(el) {
                observer.observe(el);
            });
        }

        // --- Smooth Scroll for anchor links ---
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // --- Hero mouse light effect ---
        var hero = document.querySelector('.hero[data-hero-mouse]');
        if (hero) {
            var rafId = null;
            var updateHeroMouse = function(clientX, clientY) {
                var rect = hero.getBoundingClientRect();
                var x = ((clientX - rect.left) / rect.width) * 100;
                var y = ((clientY - rect.top) / rect.height) * 100;
                hero.style.setProperty('--hero-mouse-x', Math.max(0, Math.min(100, x)).toFixed(2) + '%');
                hero.style.setProperty('--hero-mouse-y', Math.max(0, Math.min(100, y)).toFixed(2) + '%');
            };

            hero.addEventListener('mousemove', function(e) {
                if (rafId !== null) {
                    cancelAnimationFrame(rafId);
                }
                rafId = requestAnimationFrame(function() {
                    updateHeroMouse(e.clientX, e.clientY);
                });
            });

            hero.addEventListener('mouseleave', function() {
                hero.style.setProperty('--hero-mouse-x', '72%');
                hero.style.setProperty('--hero-mouse-y', '30%');
            });
        }

        // --- Confirm delete forms ---
        document.querySelectorAll('form[data-confirm]').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var msg = form.dataset.confirm || 'Tem certeza que deseja remover este item?';
                Elo42.confirm({
                    title: 'Confirmar remoção',
                    text: msg,
                    confirmLabel: 'Remover',
                    danger: true,
                    onConfirm: function() { form.submit(); }
                });
            });
        });

    });
})();
