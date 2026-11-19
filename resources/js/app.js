import * as bootstrap from 'bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.bootstrap = bootstrap;
window.Alpine = Alpine;
window.Chart = Chart;

/* ==========================================================================
   Application shell behaviour
   ========================================================================== */

const STORAGE_COLLAPSED = 'erp.sidebar.collapsed';
const STORAGE_GROUPS = 'erp.sidebar.groups';
const desktopQuery = window.matchMedia('(min-width: 992px)');

function readGroups() {
    try {
        const raw = window.localStorage.getItem(STORAGE_GROUPS);
        const value = raw ? JSON.parse(raw) : [];
        return Array.isArray(value) ? value : [];
    } catch (error) {
        return [];
    }
}

function writeGroups(groups) {
    try {
        window.localStorage.setItem(STORAGE_GROUPS, JSON.stringify([...new Set(groups)]));
    } catch (error) {
        /* Storage may be unavailable in private mode — navigation still works. */
    }
}

function initSidebar() {
    const shell = document.getElementById('appShell');
    const sidebar = document.getElementById('appSidebar');
    const backdrop = document.querySelector('[data-sidebar-backdrop]');
    const toggle = document.querySelector('[data-sidebar-toggle]');

    if (!shell || !sidebar || !toggle) {
        return;
    }

    const isDesktop = () => desktopQuery.matches;
    const setCollapsed = (collapsed) => {
        shell.classList.toggle('is-collapsed', collapsed);
        try {
            window.localStorage.setItem(STORAGE_COLLAPSED, collapsed ? '1' : '0');
        } catch (error) {
            /* ignore */
        }
        toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
    };

    try {
        if (isDesktop() && window.localStorage.getItem(STORAGE_COLLAPSED) === '1') {
            shell.classList.add('is-collapsed');
        }
    } catch (error) {
        /* Storage can be unavailable; keep the expanded default. */
    }

    const setMobileOpen = (open) => {
        shell.classList.toggle('is-mobile-open', open);
        document.body.classList.toggle('sidebar-open', open);
        sidebar.inert = !isDesktop() && !open;
        if (backdrop) {
            backdrop.setAttribute('aria-hidden', open ? 'false' : 'true');
        }
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    };

    // Initial inert state for mobile (closed off-canvas must not be tabbable).
    if (!isDesktop()) {
        sidebar.inert = true;
        toggle.setAttribute('aria-expanded', 'false');
    }

    const storedGroups = readGroups();
    const groupButtons = [...sidebar.querySelectorAll('[data-sidebar-group]')];
    const activeGroup = groupButtons
        .map((button) => button.closest('.sidebar-group'))
        .find((group) => group && (group.classList.contains('is-active') || group.dataset.open === 'true'));
    const activeKey = activeGroup?.dataset.menuGroup || '';
    const rememberedKey = storedGroups.at(-1) || '';
    const initialOpenKey = activeKey || rememberedKey;

    groupButtons.forEach((button) => {
        const group = button.closest('.sidebar-group');
        if (!group) {
            return;
        }

        const key = group.dataset.menuGroup || '';
        const submenu = group.querySelector('.sidebar-submenu');
        const shouldOpen = key === initialOpenKey;

        group.classList.toggle('is-open', shouldOpen);
        button.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
        if (submenu) {
            submenu.inert = !shouldOpen;
        }

        button.addEventListener('click', () => {
            if (isDesktop() && shell.classList.contains('is-collapsed')) {
                setCollapsed(false);
            }

            const opening = !group.classList.contains('is-open');
            groupButtons.forEach((groupButton) => {
                const otherGroup = groupButton.closest('.sidebar-group');
                if (!otherGroup) {
                    return;
                }

                const isOpen = opening && otherGroup === group;
                otherGroup.classList.toggle('is-open', isOpen);
                groupButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                const otherSubmenu = otherGroup.querySelector('.sidebar-submenu');
                if (otherSubmenu) {
                    otherSubmenu.inert = !isOpen;
                }
            });

            writeGroups(opening ? [key] : []);
        });
    });

    toggle.addEventListener('click', () => {
        if (isDesktop()) {
            setCollapsed(!shell.classList.contains('is-collapsed'));
            return;
        }

        const opening = !shell.classList.contains('is-mobile-open');
        setMobileOpen(opening);

        if (opening) {
            const first = sidebar.querySelector('a[href], button:not([disabled])');
            first?.focus({ preventScroll: true });
        }
    });

    const closeMobile = () => {
        if (isDesktop()) {
            return;
        }
        setMobileOpen(false);
        toggle.focus({ preventScroll: true });
    };

    backdrop?.addEventListener('click', closeMobile);

    sidebar.querySelectorAll('a[href]').forEach((link) => {
        link.addEventListener('click', () => {
            if (!isDesktop()) {
                setMobileOpen(false);
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && shell.classList.contains('is-mobile-open')) {
            closeMobile();
        }

        if (event.key !== 'Tab' || isDesktop() || !shell.classList.contains('is-mobile-open')) {
            return;
        }

        const focusable = [...sidebar.querySelectorAll('a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])')]
            .filter((element) => element.offsetParent !== null);

        if (focusable.length === 0) {
            return;
        }

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    });

    const handleViewportChange = () => {
        if (isDesktop()) {
            shell.classList.remove('is-mobile-open');
            document.body.classList.remove('sidebar-open');
            sidebar.inert = false;
            if (backdrop) {
                backdrop.setAttribute('aria-hidden', 'true');
            }
            toggle.setAttribute('aria-expanded', shell.classList.contains('is-collapsed') ? 'false' : 'true');
        } else {
            sidebar.inert = !shell.classList.contains('is-mobile-open');
        }
    };

    desktopQuery.addEventListener?.('change', handleViewportChange);
    window.addEventListener('resize', handleViewportChange, { passive: true });

    // Keep the active destination visible in long menus.
    window.requestAnimationFrame(() => {
        sidebar.querySelector('.sidebar-link.is-active')?.scrollIntoView({ block: 'nearest' });
    });

    initSidebarTooltips(shell, sidebar);
}

function initSidebarTooltips(shell, sidebar) {
    const tooltip = document.getElementById('sidebarTooltip');

    if (!tooltip) {
        return;
    }

    let activeButton = null;

    const hide = () => {
        tooltip.classList.remove('is-visible');
        tooltip.hidden = true;
        activeButton = null;
    };

    const show = (button) => {
        if (!shell.classList.contains('is-collapsed') || !desktopQuery.matches) {
            return;
        }

        const label = button.closest('.sidebar-group')?.querySelector('.sidebar-group__label')?.textContent?.trim()
            || button.getAttribute('aria-label')
            || '';

        if (!label) {
            return;
        }

        activeButton = button;
        tooltip.textContent = label;
        tooltip.hidden = false;
        tooltip.classList.add('is-visible');

        const rect = button.getBoundingClientRect();
        tooltip.style.left = `${Math.round(rect.right + 12)}px`;
        tooltip.style.top = `${Math.round(rect.top + rect.height / 2)}px`;
    };

    sidebar.querySelectorAll('.sidebar-group__toggle').forEach((button) => {
        button.addEventListener('mouseenter', () => show(button));
        button.addEventListener('focus', () => show(button));
        button.addEventListener('mouseleave', hide);
        button.addEventListener('blur', hide);
        button.addEventListener('click', hide);
    });

    window.addEventListener('scroll', hide, { passive: true });
    window.addEventListener('resize', hide, { passive: true });
}

/* ==========================================================================
   Theme
   ========================================================================== */

function applyTheme(theme) {
    const next = theme === 'dark' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-bs-theme', next);
    document.cookie = `theme=${next}; path=/; max-age=31536000; SameSite=Lax`;
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.setAttribute('aria-pressed', next === 'dark' ? 'true' : 'false');
        button.setAttribute('aria-label', next === 'dark' ? 'Switch to light theme' : 'Switch to dark theme');
    });
}

window.toggleTheme = function toggleTheme() {
    const current = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
    applyTheme(current === 'dark' ? 'light' : 'dark');
};

function initTheme() {
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', window.toggleTheme);
        const dark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        button.setAttribute('aria-pressed', dark ? 'true' : 'false');
        button.setAttribute('aria-label', dark ? 'Switch to light theme' : 'Switch to dark theme');
    });
}

/* ==========================================================================
   Password show / hide
   ========================================================================== */

function initPasswordToggles() {
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        const targetId = button.getAttribute('data-password-toggle');
        const input = targetId ? document.getElementById(targetId) : null;

        if (!input) {
            return;
        }

        button.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();

            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.setAttribute('aria-pressed', show ? 'true' : 'false');
            button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            button.querySelector('[data-eye-show]')?.classList.toggle('d-none', show);
            button.querySelector('[data-eye-hide]')?.classList.toggle('d-none', !show);

            input.focus({ preventScroll: true });
            const end = input.value.length;
            try {
                input.setSelectionRange(end, end);
            } catch (error) {
                /* Some input types do not support selection; value is unchanged. */
            }
        });
    });
}

/* ==========================================================================
   Submit loading state
   ========================================================================== */

function initLoadingForms() {
    document.querySelectorAll('form[data-loading-form]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (form.dataset.submitting === '1') {
                event.preventDefault();
                return;
            }

            if (!form.checkValidity()) {
                return;
            }

            form.dataset.submitting = '1';

            const button = form.querySelector('[data-loading-button]');
            if (!button) {
                return;
            }

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.querySelector('[data-loading-label]')?.classList.add('d-none');
            button.querySelector('[data-loading-spinner]')?.classList.remove('d-none');
            button.querySelector('[data-loading-text]')?.classList.remove('d-none');
        });
    });
}

/* ==========================================================================
   Small UI helpers
   ========================================================================== */

function initAutoDismissAlerts() {
    document.querySelectorAll('[data-autodismiss]').forEach((alert) => {
        window.setTimeout(() => {
            if (!alert.isConnected) {
                return;
            }
            if (window.bootstrap?.Alert) {
                window.bootstrap.Alert.getOrCreateInstance(alert).close();
            } else {
                alert.remove();
            }
        }, 8000);
    });
}

function initPrintButtons() {
    document.querySelectorAll('[data-print]').forEach((button) => {
        button.addEventListener('click', () => window.print());
    });
}

function boot() {
    initSidebar();
    initTheme();
    initPasswordToggles();
    initLoadingForms();
    initAutoDismissAlerts();
    initPrintButtons();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}

Alpine.start();
