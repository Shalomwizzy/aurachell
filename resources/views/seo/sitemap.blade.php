<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Static pages --}}
    @foreach([
        ['/', 'weekly', '1.0'],
        ['/shop', 'daily', '0.9'],
        ['/about', 'monthly', '0.6'],
        ['/contact', 'monthly', '0.5'],
        ['/faq', 'monthly', '0.5'],
        ['/shipping-returns', 'monthly', '0.4'],
    ] as [$path, $freq, $priority])
    <url>
        <loc>{{ url($path) }}</loc>
        <changefreq>{{ $freq }}</changefreq>
        <priority>{{ $priority }}</priority>
    </url>
    @endforeach

    {{-- Categories --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ url('/shop?category=' . $category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Products --}}
    @foreach($products as $product)
    <url>
        <loc>{{ route('product.show', $product->slug) }}</loc>
        <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

</urlset>
