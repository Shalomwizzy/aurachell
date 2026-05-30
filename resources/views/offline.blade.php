<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>You're Offline — Aurachell</title>
    <meta name="theme-color" content="#371220">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --mahogany: #371220;
            --sand: rgba(55,18,32,0.08);
            --caramel: #371220;
            --surface: #F7F2EB;
            --text: #371220;
        }
        body {
            background: var(--surface);
            color: var(--text);
            font-family: Georgia, 'Times New Roman', serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
        }
        .logo {
            font-size: 1.5rem;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--mahogany);
            margin-bottom: 3rem;
        }
        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            opacity: 0.35;
        }
        h1 {
            font-size: clamp(1.5rem, 5vw, 2.25rem);
            font-weight: 400;
            letter-spacing: 0.05em;
            color: var(--mahogany);
            margin-bottom: 1rem;
        }
        p {
            font-size: 1rem;
            line-height: 1.7;
            color: rgba(55,18,32,0.65);
            max-width: 380px;
            margin: 0 auto 2.5rem;
        }
        .divider {
            width: 48px;
            height: 1px;
            background: var(--caramel);
            margin: 2rem auto;
        }
        .btn {
            display: inline-block;
            padding: 0.85rem 2.5rem;
            background: var(--mahogany);
            color: var(--surface);
            font-family: Georgia, serif;
            font-size: 0.8rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.85; }
    </style>
</head>
<body>
    <div class="logo">Aurachell</div>

    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
        <line x1="12" y1="22" x2="12" y2="13" stroke-linecap="round"/>
        <line x1="9" y1="16" x2="12" y2="13" stroke-linecap="round"/>
        <line x1="15" y1="16" x2="12" y2="13" stroke-linecap="round"/>
    </svg>

    <h1>You're Offline</h1>
    <div class="divider"></div>
    <p>It looks like you've lost your connection. Check your internet and try again — your cart and wishlist will be waiting for you.</p>

    <button class="btn" onclick="window.location.reload()">Try Again</button>
</body>
</html>
