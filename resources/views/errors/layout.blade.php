<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — Aurachell</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#371220">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --mahogany: #371220;
            --sand:     rgba(55,18,32,0.08);
            --caramel:  #371220;
            --surface:  #FAF5ED;
            --text:     #371220;
        }
        html { font-size: 16px; }
        body {
            background: var(--surface);
            color: var(--text);
            font-family: Georgia, 'Times New Roman', serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        /* ── Nav ── */
        .nav {
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: center;
            border-bottom: 1px solid rgba(55,18,32,0.25);
        }
        .nav a {
            font-size: 1.1rem;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--mahogany);
            text-decoration: none;
        }
        /* ── Main ── */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 4rem 2rem;
        }
        .code {
            font-size: clamp(5rem, 18vw, 9rem);
            font-weight: 400;
            letter-spacing: -0.02em;
            color: var(--mahogany);
            line-height: 1;
            opacity: 0.12;
            user-select: none;
        }
        .icon-wrap {
            margin: -1.5rem auto 2rem;
            position: relative;
        }
        .icon-wrap svg {
            width: 56px;
            height: 56px;
            color: var(--caramel);
        }
        h1 {
            font-size: clamp(1.4rem, 4vw, 2rem);
            font-weight: 400;
            letter-spacing: 0.05em;
            color: var(--mahogany);
            margin-bottom: 1rem;
        }
        .divider {
            width: 40px;
            height: 1px;
            background: var(--caramel);
            margin: 1.25rem auto;
        }
        p {
            font-size: 0.975rem;
            line-height: 1.75;
            color: rgba(55,18,32,0.6);
            max-width: 400px;
            margin: 0 auto 2.5rem;
        }
        .actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            font-family: Georgia, serif;
            font-size: 0.75rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            text-decoration: none;
            transition: opacity 0.2s;
            cursor: pointer;
            border: none;
        }
        .btn:hover { opacity: 0.8; }
        .btn-primary { background: var(--mahogany); color: var(--surface); }
        .btn-ghost   { background: transparent; color: var(--mahogany); border: 1px solid rgba(55,18,32,0.35); }
        /* ── Footer ── */
        .foot {
            padding: 1.5rem 2rem;
            text-align: center;
            font-size: 0.75rem;
            color: rgba(55,18,32,0.35);
            letter-spacing: 0.05em;
            border-top: 1px solid rgba(55,18,32,0.25);
        }
    </style>
</head>
<body>
    <nav class="nav">
        <a href="/">Aurachell</a>
    </nav>

    <main class="main">
        <div class="code">@yield('code')</div>
        <div class="icon-wrap">@yield('icon')</div>
        <h1>@yield('heading')</h1>
        <div class="divider"></div>
        <p>@yield('message')</p>
        <div class="actions">@yield('actions')</div>
    </main>

    <footer class="foot">
        &copy; {{ date('Y') }} Aurachell. All rights reserved.
    </footer>
</body>
</html>
