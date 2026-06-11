@extends('layouts.admin')
@section('title', 'AI Studio')
@section('breadcrumb', 'AI Studio')

@section('content')
<div class="p-6 lg:p-8">

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-xl font-semibold" style="color:var(--adm-text);">AI Studio</h1>
        <p class="text-xs mt-1" style="color:var(--adm-muted);">Gemini for analysis &amp; generation · Groq for fast conversations</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Gemini Panel --}}
    <div class="flex flex-col" style="background:var(--adm-surface);border:1px solid var(--adm-border);min-height:580px;">
        <div class="flex items-center gap-3 px-5 py-4" style="border-bottom:1px solid var(--adm-border);">
            <div class="w-7 h-7 flex items-center justify-center shrink-0" style="background:rgba(55,18,32,0.3);">
                <svg class="w-4 h-4" style="color:var(--adm-gold)" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium" style="color:var(--adm-text);">Gemini / AI Writer</p>
                <p class="text-xs" style="color:var(--adm-muted);">Content generation, SEO, analysis · auto-falls back to Groq</p>
            </div>
        </div>

        {{-- Quick prompts --}}
        <div class="px-5 py-3 flex flex-wrap gap-2" style="border-bottom:1px solid var(--adm-border);">
            @foreach([
                'Suggest 5 blog topics for a luxury aromatherapy brand',
                'Write a product description for a cedarwood diffuser',
                'Generate SEO keywords for luxury diffusers Nigeria',
                'Write a Christmas email campaign subject line',
            ] as $quick)
            <button type="button" onclick="document.getElementById('gemini-prompt').value = {{ json_encode($quick) }}"
                    class="text-xs px-2.5 py-1 transition-opacity hover:opacity-80"
                    style="background:rgba(55,18,32,0.08);color:var(--adm-muted);border:1px solid var(--adm-border);">
                {{ $quick }}
            </button>
            @endforeach
        </div>

        <div class="flex-1 p-5 space-y-4 overflow-y-auto">
            <textarea id="gemini-prompt" rows="5"
                      placeholder="Ask anything — generate product descriptions, blog content, email campaigns, SEO copy…"
                      onkeydown="if(event.ctrlKey && event.key==='Enter'){event.preventDefault();aiSendGemini();}"
                      class="w-full p-3 text-sm resize-none focus:outline-none"
                      style="background:rgba(255,255,255,0.04);border:1px solid var(--adm-border);color:var(--adm-text);"></textarea>

            <div id="gemini-result-wrap" style="display:none;"
                 class="p-4 text-sm leading-relaxed whitespace-pre-wrap font-mono overflow-y-auto"
                 style="background:rgba(255,255,255,0.03);border:1px solid var(--adm-border);color:var(--adm-text);max-height:300px;">
                <div id="gemini-loading-indicator" class="flex items-center gap-2" style="display:none;color:var(--adm-muted);">
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Generating…
                </div>
                <span id="gemini-result-text"></span>
            </div>
        </div>

        <div class="px-5 py-4 flex gap-3" style="border-top:1px solid var(--adm-border);">
            <button type="button" id="gemini-send-btn" onclick="aiSendGemini()"
                    class="flex-1 py-2.5 text-xs tracking-wider uppercase font-medium flex items-center justify-center gap-2 transition-opacity hover:opacity-90"
                    style="background:#371220;color:#FFFFFF;">
                <svg id="gemini-send-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <svg id="gemini-send-spin" class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span id="gemini-send-label">Generate</span>
            </button>
            <button type="button" onclick="aiClearGemini()"
                    class="px-4 py-2.5 text-xs tracking-wider uppercase transition-opacity hover:opacity-70"
                    style="background:rgba(55,18,32,0.08);color:var(--adm-muted);">Clear</button>
        </div>
    </div>

    {{-- Groq Chat Panel --}}
    <div class="flex flex-col" style="background:var(--adm-surface);border:1px solid var(--adm-border);min-height:580px;">
        <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid var(--adm-border);">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 flex items-center justify-center shrink-0" style="background:rgba(55,18,32,0.15);">
                    <svg class="w-4 h-4" style="color:var(--adm-gold)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color:var(--adm-text);">Groq Chat</p>
                    <p class="text-xs" style="color:var(--adm-muted);">Fast conversation · Strategy · Q&amp;A</p>
                </div>
            </div>
            <button type="button" onclick="aiClearGroq()"
                    class="text-xs hover:opacity-70 transition-opacity" style="color:var(--adm-muted);">Clear chat</button>
        </div>

        <div id="groq-messages" class="flex-1 p-5 space-y-4 overflow-y-auto" style="max-height:420px;">
            {{-- Populated by JS --}}
        </div>

        <div class="px-5 py-4 flex gap-3" style="border-top:1px solid var(--adm-border);">
            <input id="groq-input" type="text"
                   placeholder="Ask anything about Aurachell…"
                   onkeydown="if(event.key==='Enter'){event.preventDefault();aiSendGroq();}"
                   class="flex-1 px-3 py-2.5 text-sm focus:outline-none"
                   style="background:rgba(255,255,255,0.04);border:1px solid var(--adm-border);color:var(--adm-text);">
            <button type="button" id="groq-send-btn" onclick="aiSendGroq()"
                    class="px-4 py-2.5 flex items-center gap-2 transition-opacity hover:opacity-90"
                    style="background:#371220;color:#FFFFFF;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </button>
        </div>
    </div>

</div>
</div>

@push('scripts')
<script>
(function(){
    var csrf = (document.querySelector('meta[name=csrf-token]') || {}).content || '';
    var geminiLoading = false;
    var groqLoading = false;
    var groqMessages = [];

    var GROQ_STARTERS = [
        'What marketing strategies work for luxury brands?',
        'How can I increase repeat purchases?',
        'Suggest a pricing strategy for premium diffusers',
    ];

    function renderGroqMessages() {
        var el = document.getElementById('groq-messages');
        if (!el) return;
        if (groqMessages.length === 0) {
            el.innerHTML = '<div class="flex flex-col items-center justify-center gap-3 py-12">' +
                '<p class="text-sm text-center" style="color:var(--adm-muted);">Start a conversation about your business</p>' +
                '<div class="flex flex-wrap gap-2 justify-center">' +
                GROQ_STARTERS.map(function(q) {
                    return '<button type="button" onclick="aiGroqStarter(' + JSON.stringify(q) + ')" ' +
                        'class="text-xs px-3 py-1.5 transition-opacity hover:opacity-80" ' +
                        'style="background:rgba(55,18,32,0.08);color:var(--adm-muted);border:1px solid var(--adm-border);">' +
                        escHtml(q) + '</button>';
                }).join('') +
                '</div></div>';
            return;
        }
        var html = '';
        groqMessages.forEach(function(msg) {
            var isUser = msg.role === 'user';
            html += '<div class="flex ' + (isUser ? 'justify-end' : 'justify-start') + '">' +
                '<div class="max-w-[80%] px-4 py-2.5 text-sm leading-relaxed" style="' +
                (isUser ? 'background:#371220;color:#FFFFFF;' : 'background:rgba(255,255,255,0.05);color:var(--adm-text);border:1px solid var(--adm-border);') +
                '">' + escHtml(msg.content) + '</div></div>';
        });
        if (groqLoading) {
            html += '<div class="flex justify-start"><div class="flex items-center gap-1.5 px-4 py-3" style="background:rgba(255,255,255,0.05);border:1px solid var(--adm-border);">' +
                '<span class="w-1.5 h-1.5 rounded-full animate-bounce" style="background:var(--adm-muted);animation-delay:0ms"></span>' +
                '<span class="w-1.5 h-1.5 rounded-full animate-bounce" style="background:var(--adm-muted);animation-delay:150ms"></span>' +
                '<span class="w-1.5 h-1.5 rounded-full animate-bounce" style="background:var(--adm-muted);animation-delay:300ms"></span>' +
                '</div></div>';
        }
        el.innerHTML = html;
        el.scrollTop = el.scrollHeight;
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    window.aiGroqStarter = function(q) {
        document.getElementById('groq-input').value = q;
        aiSendGroq();
    };

    window.aiSendGemini = function() {
        var prompt = (document.getElementById('gemini-prompt') || {}).value || '';
        if (!prompt.trim() || geminiLoading) return;
        geminiLoading = true;
        var btn = document.getElementById('gemini-send-btn');
        var icon = document.getElementById('gemini-send-icon');
        var spin = document.getElementById('gemini-send-spin');
        var lbl = document.getElementById('gemini-send-label');
        var wrap = document.getElementById('gemini-result-wrap');
        var loadInd = document.getElementById('gemini-loading-indicator');
        var resultText = document.getElementById('gemini-result-text');
        btn.disabled = true; btn.style.opacity = '0.5';
        icon.style.display = 'none'; spin.style.display = 'inline';
        lbl.textContent = 'Generating…';
        wrap.style.display = 'block';
        loadInd.style.display = 'flex';
        resultText.textContent = '';

        fetch('{{ route("admin.ai.gemini") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ prompt: prompt, max_tokens: 1500 })
        })
        .then(function(res) {
            if (res.status === 401) throw new Error('Session expired. Please refresh the page and log in again.');
            return res.json();
        })
        .then(function(data) {
            if (data.success) {
                var text = typeof data.data === 'object' ? JSON.stringify(data.data, null, 2) : data.data;
                resultText.textContent = (data.provider === 'groq' ? '[Groq fallback] ' : '') + text;
            } else {
                resultText.textContent = '⚠ ' + (data.error || 'Unknown error');
            }
        })
        .catch(function(e) {
            resultText.textContent = '⚠ ' + e.message;
        })
        .finally(function() {
            geminiLoading = false;
            btn.disabled = false; btn.style.opacity = '';
            icon.style.display = 'inline'; spin.style.display = 'none';
            lbl.textContent = 'Generate';
            loadInd.style.display = 'none';
        });
    };

    window.aiClearGemini = function() {
        var prompt = document.getElementById('gemini-prompt');
        var wrap = document.getElementById('gemini-result-wrap');
        var resultText = document.getElementById('gemini-result-text');
        if (prompt) prompt.value = '';
        if (resultText) resultText.textContent = '';
        if (wrap) wrap.style.display = 'none';
    };

    window.aiSendGroq = function() {
        var input = document.getElementById('groq-input');
        var msg = (input ? input.value.trim() : '');
        if (!msg || groqLoading) return;
        input.value = '';
        groqMessages.push({ role: 'user', content: msg });
        groqLoading = true;
        renderGroqMessages();

        var messages = [
            { role: 'system', content: 'You are a helpful AI assistant for Aurachell, a luxury Nigerian aromatherapy and home diffuser brand. Be concise, professional, and helpful.' }
        ].concat(groqMessages);

        fetch('{{ route("admin.ai.groq") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ messages: messages, max_tokens: 800 })
        })
        .then(function(res) {
            if (res.status === 401) throw new Error('Session expired. Please refresh the page.');
            return res.json();
        })
        .then(function(data) {
            if (data.success) {
                groqMessages.push({ role: 'assistant', content: data.content });
            } else {
                groqMessages.push({ role: 'assistant', content: '⚠ ' + (data.error || 'Unknown error') });
            }
        })
        .catch(function(e) {
            groqMessages.push({ role: 'assistant', content: '⚠ ' + e.message });
        })
        .finally(function() {
            groqLoading = false;
            renderGroqMessages();
        });
    };

    window.aiClearGroq = function() {
        groqMessages = [];
        renderGroqMessages();
    };

    renderGroqMessages();
})();
</script>
@endpush
@endsection
