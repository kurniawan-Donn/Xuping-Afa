{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ route('website.index') }}</loc><changefreq>daily</changefreq><priority>1.0</priority></url>
    <url><loc>{{ route('website.products.index') }}</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
    <url><loc>{{ route('website.about') }}</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>
@foreach ($products as $product)
    <url><loc>{{ route('website.products.show', $product) }}</loc><lastmod>{{ $product->updated_at?->toAtomString() }}</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>
@endforeach
</urlset>
