<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aurachell — Setup Installer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --mahogany: #371220;
            --sand: rgba(55,18,32,0.08);
            --surface: #F7F2EB;
            --text-dark: #371220;
            --muted: rgba(201,169,111,0.65);
        }
        [x-cloak] { display: none !important; }
        body { background: var(--surface); color: var(--text-dark); font-family: system-ui, -apple-system, sans-serif; }
        .btn-primary { background: var(--mahogany); color: white; padding: 12px 28px; font-size: 13px; letter-spacing: .12em; text-transform: uppercase; font-weight: 500; border-radius: 4px; transition: opacity .15s; }
        .btn-primary:hover { opacity: .88; }
        .btn-primary:disabled { opacity: .45; cursor: not-allowed; }
        .btn-secondary { border: 1px solid var(--sand); color: var(--muted); padding: 12px 24px; font-size: 13px; letter-spacing: .12em; text-transform: uppercase; font-weight: 500; border-radius: 4px; transition: border-color .15s; background: transparent; }
        .btn-secondary:hover { border-color: var(--mahogany); color: var(--mahogany); }
        .field-label { display: block; font-size: 11px; letter-spacing: .15em; text-transform: uppercase; color: var(--muted); margin-bottom: 6px; font-weight: 500; }
        .field-input { width: 100%; border: 1px solid var(--sand); background: white; padding: 10px 14px; font-size: 14px; color: var(--text-dark); border-radius: 4px; transition: border-color .15s, box-shadow .15s; outline: none; }
        .field-input:focus { border-color: var(--mahogany); box-shadow: 0 0 0 2px rgba(55,18,32,.12); }
        .step-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; flex-shrink: 0; transition: all .2s; }
        .step-line { flex: 1; height: 1px; background: var(--sand); margin: 0 8px; }
    </style>
</head>
<body>
<div x-data="installer()" x-init="init()" class="min-h-screen flex flex-col">

    {{-- Header --}}
    <div class="border-b" style="border-color: var(--sand); background: white;">
        <div class="max-w-2xl mx-auto px-6 py-5 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--mahogany);">
                <span class="text-white text-xs font-bold">A</span>
            </div>
            <div>
                <p class="font-semibold text-sm" style="color: var(--text-dark);">Aurachell</p>
                <p class="text-xs" style="color: var(--muted);">Setup Wizard</p>
            </div>
        </div>
    </div>

    {{-- Progress Steps --}}
    <div class="max-w-2xl mx-auto px-6 pt-10 w-full">
        <div class="flex items-center mb-10">
            <template x-for="(s, i) in steps" :key="i">
                <div class="flex items-center" :class="i < steps.length - 1 ? 'flex-1' : ''">
                    <div class="flex flex-col items-center gap-1">
                        <div class="step-dot"
                             :style="step > i+1 ? 'background:var(--mahogany);color:white;' : step === i+1 ? 'border:2px solid var(--mahogany);color:var(--mahogany);background:white;' : 'border:1px solid var(--sand);color:var(--muted);background:white;'">
                            <template x-if="step > i+1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <template x-if="step <= i+1">
                                <span x-text="i+1"></span>
                            </template>
                        </div>
                        <span class="text-xs whitespace-nowrap hidden sm:block" :style="step === i+1 ? 'color:var(--mahogany);font-weight:600;' : 'color:var(--muted);'" x-text="s"></span>
                    </div>
                    <div x-if="i < steps.length - 1" class="step-line" :style="step > i+1 ? 'background:var(--mahogany);' : ''"></div>
                </div>
            </template>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-2xl mx-auto px-6 pb-16 w-full flex-1">

        {{-- ===== STEP 1: PRE-FLIGHT ===== --}}
        <div x-show="step === 1" x-cloak>
            <h2 class="text-2xl font-semibold mb-1" style="color:var(--text-dark);">System Check</h2>
            <p class="text-sm mb-8" style="color:var(--muted);">Verifying your server meets all requirements before we begin.</p>

            <div class="bg-white border rounded-lg overflow-hidden mb-6" style="border-color:var(--sand);">
                <template x-if="checks.length === 0">
                    <div class="p-8 text-center text-sm" style="color:var(--muted);">
                        <svg class="w-6 h-6 mx-auto mb-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Checking requirements…
                    </div>
                </template>
                <template x-for="(c, i) in checks" :key="i">
                    <div class="flex items-center justify-between px-5 py-3.5" :class="i > 0 ? 'border-t' : ''" style="border-color:var(--sand);">
                        <span class="text-sm" style="color:var(--text-dark);" x-text="c.label"></span>
                        <div class="flex items-center gap-2">
                            <span x-show="c.detail" class="text-xs" style="color:var(--muted);" x-text="c.detail"></span>
                            <svg x-show="c.pass" class="w-5 h-5 text-mahogany" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <svg x-show="!c.pass" class="w-5 h-5 text-mahogany" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                    </div>
                </template>
            </div>

            <template x-if="checks.length > 0 && !checksReady">
                <div class="p-4 rounded-lg text-sm mb-6" style="background:rgba(55,18,32,0.06);border:1px solid rgba(55,18,32,0.20);color:#371220;">
                    <strong>Some requirements are not met.</strong> Please fix the issues above and refresh this page before continuing. Contact your hosting provider if you need help enabling PHP extensions or fixing folder permissions.
                </div>
            </template>

            <div class="flex justify-end">
                <button class="btn-primary" :disabled="!checksReady || checks.length === 0" @click="step = 2">
                    Continue →
                </button>
            </div>
        </div>

        {{-- ===== STEP 2: DATABASE ===== --}}
        <div x-show="step === 2" x-cloak>
            <h2 class="text-2xl font-semibold mb-1" style="color:var(--text-dark);">Database Connection</h2>
            <p class="text-sm mb-8" style="color:var(--muted);">Enter your MySQL database credentials. These are provided by your hosting provider.</p>

            <div class="bg-white border rounded-lg p-6 mb-6" style="border-color:var(--sand);">
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="col-span-2">
                        <label class="field-label">Database Host</label>
                        <input type="text" x-model="form.db_host" class="field-input" placeholder="127.0.0.1 or localhost">
                    </div>
                    <div>
                        <label class="field-label">Port</label>
                        <input type="number" x-model="form.db_port" class="field-input" placeholder="3306">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="field-label">Database Name</label>
                    <input type="text" x-model="form.db_database" class="field-input" placeholder="aurachell">
                </div>
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="field-label">Username</label>
                        <input type="text" x-model="form.db_username" class="field-input" placeholder="root">
                    </div>
                    <div>
                        <label class="field-label">Password</label>
                        <input type="password" x-model="form.db_password" class="field-input" placeholder="Leave blank if none">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button class="btn-secondary" :disabled="dbTesting" @click="testDatabase()">
                        <span x-show="!dbTesting">Test Connection</span>
                        <span x-show="dbTesting">Testing…</span>
                    </button>
                    <div x-show="dbTested" class="flex items-center gap-2 text-sm">
                        <svg x-show="dbTestPassed" class="w-5 h-5 text-mahogany" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <svg x-show="!dbTestPassed" class="w-5 h-5 text-mahogany" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span x-text="dbTestMessage" :class="dbTestPassed ? 'text-mahogany' : 'text-mahogany'"></span>
                    </div>
                </div>

                <template x-if="dbTested && !dbTestPassed">
                    <p class="text-xs mt-3" style="color:var(--muted);">Common fixes: check the database name is correct, ensure the user has access to that database, and confirm the host matches what your hosting provides (often <code>localhost</code> on shared hosting).</p>
                </template>
            </div>

            <div class="flex justify-between">
                <button class="btn-secondary" @click="step = 1">← Back</button>
                <button class="btn-primary" :disabled="!dbTestPassed" @click="step = 3">Continue →</button>
            </div>
        </div>

        {{-- ===== STEP 3: ADMIN ACCOUNT ===== --}}
        <div x-show="step === 3" x-cloak>
            <h2 class="text-2xl font-semibold mb-1" style="color:var(--text-dark);">Admin Account</h2>
            <p class="text-sm mb-8" style="color:var(--muted);">This creates your super admin login. Use a strong password — this account has full control of the store.</p>

            <div class="bg-white border rounded-lg p-6 mb-6" style="border-color:var(--sand);">
                <div class="mb-4">
                    <label class="field-label">Full Name</label>
                    <input type="text" x-model="form.admin_name" class="field-input" placeholder="Store Owner">
                </div>
                <div class="mb-4">
                    <label class="field-label">Email Address</label>
                    <input type="email" x-model="form.admin_email" class="field-input" placeholder="admin@yourdomain.com">
                </div>
                <div class="grid grid-cols-2 gap-4 mb-2">
                    <div>
                        <label class="field-label">Password</label>
                        <input type="password" x-model="form.admin_password" class="field-input" placeholder="Min 8 characters">
                    </div>
                    <div>
                        <label class="field-label">Confirm Password</label>
                        <input type="password" x-model="form.admin_password_confirmation" class="field-input" placeholder="Repeat password">
                    </div>
                </div>
                <template x-if="form.admin_password && form.admin_password_confirmation && form.admin_password !== form.admin_password_confirmation">
                    <p class="text-xs text-mahogany mt-2">Passwords do not match.</p>
                </template>
            </div>

            <div class="flex justify-between">
                <button class="btn-secondary" @click="step = 2">← Back</button>
                <button class="btn-primary" :disabled="!adminStepValid()" @click="step = 4">Continue →</button>
            </div>
        </div>

        {{-- ===== STEP 4: STORE SETTINGS ===== --}}
        <div x-show="step === 4" x-cloak>
            <h2 class="text-2xl font-semibold mb-1" style="color:var(--text-dark);">Store Settings</h2>
            <p class="text-sm mb-8" style="color:var(--muted);">Basic details about your store. You can change any of these later in Admin → Settings.</p>

            <div class="bg-white border rounded-lg p-6 mb-6" style="border-color:var(--sand);">
                <div class="mb-4">
                    <label class="field-label">Store Name</label>
                    <input type="text" x-model="form.store_name" class="field-input" placeholder="Aurachell">
                </div>
                <div class="mb-4">
                    <label class="field-label">Store Email</label>
                    <input type="email" x-model="form.store_email" class="field-input" placeholder="hello@yourdomain.com">
                    <p class="text-xs mt-1" style="color:var(--muted);">Used as the "From" address for all customer emails.</p>
                </div>
                <div class="mb-4">
                    <label class="field-label">Site URL</label>
                    <input type="url" x-model="form.app_url" class="field-input" placeholder="https://yourdomain.com">
                    <p class="text-xs mt-1" style="color:var(--muted);">Must match the domain exactly, including https://</p>
                </div>
                <div>
                    <label class="field-label">Currency Symbol</label>
                    <input type="text" x-model="form.currency" class="field-input" placeholder="₦" style="max-width:120px;">
                </div>
            </div>

            <div class="flex justify-between">
                <button class="btn-secondary" @click="step = 3">← Back</button>
                <button class="btn-primary" :disabled="!storeStepValid()" @click="runInstall()">
                    <span x-show="!installing">Install Aurachell →</span>
                    <span x-show="installing" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Installing…
                    </span>
                </button>
            </div>

            <template x-if="installError">
                <div class="mt-4 p-4 rounded-lg text-sm" style="background:rgba(55,18,32,0.06);border:1px solid rgba(55,18,32,0.20);color:#371220;">
                    <strong>Installation failed:</strong> <span x-text="installError"></span>
                </div>
            </template>
        </div>

        {{-- ===== STEP 5: DONE ===== --}}
        <div x-show="step === 5" x-cloak class="text-center py-8">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background:rgba(55,18,32,.1);">
                <svg class="w-10 h-10" style="color:var(--mahogany);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-3xl font-semibold mb-3" style="color:var(--text-dark);">Aurachell is ready!</h2>
            <p class="text-sm mb-2" style="color:var(--muted);">Your store has been installed and configured.</p>
            <p class="text-sm mb-10" style="color:var(--muted);">Log in with <strong x-text="form.admin_email"></strong> and the password you chose.</p>

            <div class="bg-white border rounded-lg p-6 mb-8 text-left max-w-sm mx-auto" style="border-color:var(--sand);">
                <p class="text-xs font-semibold mb-3" style="color:var(--muted); letter-spacing:.15em; text-transform:uppercase;">Next steps</p>
                <ul class="space-y-2 text-sm" style="color:var(--text-dark);">
                    <li class="flex items-start gap-2"><span style="color:var(--mahogany);">→</span> Upload your logo — Admin → Settings → Appearance</li>
                    <li class="flex items-start gap-2"><span style="color:var(--mahogany);">→</span> Add Paystack live keys — Admin → Settings → Payments</li>
                    <li class="flex items-start gap-2"><span style="color:var(--mahogany);">→</span> Configure SMTP mail — update .env MAIL_* values</li>
                    <li class="flex items-start gap-2"><span style="color:var(--mahogany);">→</span> Create your first product — Admin → Products</li>
                </ul>
            </div>

            <a href="/admin" class="btn-primary inline-block">Go to Admin Panel →</a>
        </div>

    </div>
</div>

<script>
function installer() {
    return {
        step: 1,
        steps: ['Requirements', 'Database', 'Admin', 'Store'],
        checks: [],
        checksReady: false,
        dbTesting: false,
        dbTested: false,
        dbTestPassed: false,
        dbTestMessage: '',
        installing: false,
        installError: '',

        form: {
            db_host: '127.0.0.1',
            db_port: '3306',
            db_database: '',
            db_username: '',
            db_password: '',
            admin_name: '',
            admin_email: '',
            admin_password: '',
            admin_password_confirmation: '',
            store_name: 'Aurachell',
            app_url: window.location.origin,
            store_email: '',
            currency: '₦',
        },

        async init() {
            const res = await fetch('/install/preflight', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            this.checks = data.checks;
            this.checksReady = data.ready;
        },

        async testDatabase() {
            this.dbTesting = true;
            this.dbTested = false;
            try {
                const res = await fetch('/install/test-database', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        db_host: this.form.db_host,
                        db_port: this.form.db_port,
                        db_database: this.form.db_database,
                        db_username: this.form.db_username,
                        db_password: this.form.db_password,
                    }),
                });
                const data = await res.json();
                this.dbTestPassed = data.success;
                this.dbTestMessage = data.message;
            } catch (e) {
                this.dbTestPassed = false;
                this.dbTestMessage = 'Request failed. Check your server is running.';
            }
            this.dbTesting = false;
            this.dbTested = true;
        },

        adminStepValid() {
            return this.form.admin_name.trim() !== ''
                && this.form.admin_email.trim() !== ''
                && this.form.admin_password.length >= 8
                && this.form.admin_password === this.form.admin_password_confirmation;
        },

        storeStepValid() {
            return this.form.store_name.trim() !== ''
                && this.form.store_email.trim() !== ''
                && this.form.app_url.trim() !== ''
                && this.form.currency.trim() !== '';
        },

        async runInstall() {
            if (this.installing) return;
            this.installing = true;
            this.installError = '';
            try {
                const res = await fetch('/install/run', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(this.form),
                });
                const data = await res.json();
                if (data.success) {
                    this.step = 5;
                } else {
                    this.installError = data.message ?? 'Installation failed. Please try again.';
                }
            } catch (e) {
                this.installError = 'Request failed. The server may have timed out. Check storage/logs/laravel.log for details.';
            }
            this.installing = false;
        },
    }
}
</script>
</body>
</html>
