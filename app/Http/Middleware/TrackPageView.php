<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    private const BOT_PATTERNS = ['bot', 'crawl', 'spider', 'slurp', 'yahoo', 'baidu', 'yandex', 'semrush', 'ahref', 'lighthouse'];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            try {
                $ua = strtolower($request->userAgent() ?? '');
                PageView::create([
                    'url' => '/'.ltrim($request->path(), '/'),
                    'referrer' => $this->parseReferrer($request->header('referer')),
                    'ip_hash' => md5($request->ip().config('app.key')),
                    'session_id' => $request->session()->getId(),
                    'device' => $this->detectDevice($ua),
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                ]);
            } catch (\Exception) {
                // Never break the page if tracking fails
            }
        }

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }
        if ($request->is('admin', 'admin/*')) {
            return false;
        }
        if ($request->is('api/*', 'cart/data', 'chatbot*', 'payment/*', 'sitemap.xml', 'robots.txt')) {
            return false;
        }
        if ($request->expectsJson()) {
            return false;
        }
        if ($response->getStatusCode() !== 200) {
            return false;
        }
        if ($this->isBot($request->userAgent() ?? '')) {
            return false;
        }

        return true;
    }

    private function isBot(string $ua): bool
    {
        $ua = strtolower($ua);
        foreach (self::BOT_PATTERNS as $pattern) {
            if (str_contains($ua, $pattern)) {
                return true;
            }
        }

        return false;
    }

    private function detectDevice(string $ua): string
    {
        if (str_contains($ua, 'ipad') || str_contains($ua, 'tablet') || (str_contains($ua, 'android') && ! str_contains($ua, 'mobile'))) {
            return 'tablet';
        }
        if (str_contains($ua, 'mobile') || str_contains($ua, 'iphone') || str_contains($ua, 'android')) {
            return 'mobile';
        }

        return 'desktop';
    }

    private function parseReferrer(?string $referer): ?string
    {
        if (! $referer) {
            return null;
        }
        $host = parse_url($referer, PHP_URL_HOST);
        if (! $host) {
            return null;
        }
        $host = preg_replace('/^www\./', '', $host);
        // Don't track self-referrals
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
        $appHost = preg_replace('/^www\./', '', $appHost ?? '');

        return ($host === $appHost) ? null : $host;
    }
}
