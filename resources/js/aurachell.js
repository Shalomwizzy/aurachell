/**
 * Aurachell — Global JS
 * Theme management, utilities, admin AI helpers
 */

/* ─── Dark / Light Mode ──────────────────────────────────────────────── */
const ThemeManager = {
    init() {
        this.apply('light');
    },
    apply(theme) {
        document.documentElement.classList.toggle('dark', theme === 'dark');
        document.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme } }));
    },
    toggle() {
        const current = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        this.apply(current === 'dark' ? 'light' : 'dark');
    },
    current() {
        return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    },
};

window.ThemeManager = ThemeManager;
ThemeManager.init();

/* ─── Alpine Data: Theme Toggle ─────────────────────────────────────── */
document.addEventListener('alpine:init', () => {
    Alpine.data('themeToggle', () => ({
        dark: document.documentElement.classList.contains('dark'),
        toggle() {
            ThemeManager.toggle();
            this.dark = !this.dark;
        },
    }));

    Alpine.data('adminSidebar', () => ({
        collapsed: localStorage.getItem('admin-sidebar') === 'collapsed',
        groups: {},
        toggle() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('admin-sidebar', this.collapsed ? 'collapsed' : 'open');
        },
        toggleGroup(name) {
            this.groups[name] = !this.groups[name];
            localStorage.setItem('admin-sidebar-group-' + name, this.groups[name] ? '1' : '0');
        },
        isGroupOpen(name) {
            const stored = localStorage.getItem('admin-sidebar-group-' + name);
            if (stored === null) return true; // open by default
            return stored === '1';
        },
        init() {
            // Restore group states
            ['catalog', 'finance', 'customers', 'marketing', 'ai', 'reports', 'system'].forEach(g => {
                this.groups[g] = this.isGroupOpen(g);
            });
        },
    }));
});

/* ─── Add to Cart — Global Helper ───────────────────────────────────── */
window.addToCartAjax = async function(productId, variantId, quantity, scentNote) {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    try {
        const res = await fetch('/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({ product_id: productId, variant_id: variantId, quantity, scent_note: scentNote || null }),
        });
        const data = await res.json();
        if (data.success) {
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
            return data;
        }
        throw new Error(data.message || 'Failed to add to cart');
    } catch (e) {
        console.error('addToCart:', e);
        throw e;
    }
};

/* ─── Admin AI Helper ───────────────────────────────────────────────── */
window.AurachellAI = {
    async gemini(prompt, maxTokens = 800) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        const res = await fetch('/admin/ai/gemini', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({ prompt, max_tokens: maxTokens }),
        });
        if (!res.ok) throw new Error('Gemini request failed');
        return res.json();
    },
    async generateDescription(productName, category) {
        const prompt = `You are a luxury brand copywriter for Aurachell, a premium Nigerian home fragrance brand.\n\nProduct: "${productName}"\nCategory: "${category || 'Home Fragrance'}"\n\nWrite the following (return as JSON):\n{\n  "short_description": "2-sentence evocative product summary (max 80 words)",\n  "description": "Rich product description with sensory details (150-200 words, HTML paragraphs allowed)",\n  "meta_title": "SEO title max 60 chars",\n  "meta_description": "SEO meta description max 155 chars"\n}`;
        return this.gemini(prompt);
    },
    async generateSEO(productName, description) {
        const prompt = `Generate SEO tags for this Aurachell product:\nProduct: "${productName}"\nDescription: "${description}"\n\nReturn JSON:\n{\n  "meta_title": "max 60 chars",\n  "meta_description": "max 155 chars",\n  "keywords": ["5-8 keywords as array"]\n}`;
        return this.gemini(prompt);
    },
};

/* ─── Notification toast ─────────────────────────────────────────────── */
window.showToast = function(message, type = 'success') {
    const el = document.createElement('div');
    el.className = `fixed top-24 right-4 z-[999] px-6 py-3 text-sm font-sans shadow-lg transition-all duration-300 translate-x-0 ${type === 'success' ? 'bg-[#371220] text-[#F7F2EB]' : 'bg-red-600 text-white'}`;
    el.textContent = message;
    document.body.appendChild(el);
    setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }, 3500);
};
