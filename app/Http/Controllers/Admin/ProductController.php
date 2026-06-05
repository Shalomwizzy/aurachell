<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\StockReservation;
use App\Traits\SecureFileUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    use SecureFileUpload;
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'primaryImage'])
            ->withSum(['stockReservations as reserved_quantity' => fn ($q) => $q->where('expires_at', '>', now())], 'quantity')
            ->withTrashed();

        if ($search = $request->q) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%"));
        }

        if ($category = $request->category) {
            $query->where('category_id', $category);
        }

        if ($request->status === 'active') {
            $query->where('is_active', true);
        }
        if ($request->status === 'inactive') {
            $query->where('is_active', false);
        }
        if ($request->status === 'trashed') {
            $query->onlyTrashed();
        }

        $products = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function lowStock(): View
    {
        $products = Product::with(['category', 'primaryImage'])
            ->withSum(['stockReservations as reserved_quantity' => fn ($q) => $q->where('expires_at', '>', now())], 'quantity')
            ->where('is_active', true)
            ->where('stock_quantity', '<=', 3)
            ->orderBy('stock_quantity', 'asc')
            ->paginate(30);

        $lowStockCount = $products->total();

        return view('admin.products.low-stock', compact('products', 'lowStockCount'));
    }

    public function stockReservations(): View
    {
        $reservations = StockReservation::with(['product.primaryImage', 'variant', 'cart.user'])
            ->where('expires_at', '>', now())
            ->orderBy('expires_at', 'asc')
            ->paginate(40);

        $totalHeld = StockReservation::where('expires_at', '>', now())->sum('quantity');

        return view('admin.products.stock-reservations', compact('reservations', 'totalHeld'));
    }

    public function create(): View
    {
        $categories = Category::active()->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'scent_notes' => 'nullable|string|max:300',
            'capacity_ml' => 'nullable|integer|min:0',
            'burn_time_hours' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'images' => 'nullable|array',
            'images.*' => 'image|max:4096',
        ]);

        $data['slug'] = $this->uniqueSlug(Str::slug($data['name']));
        $data['sku'] = $data['sku'] ?? ('AUR-'.strtoupper(Str::random(6)));
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $filename = uniqid('prod_') . '.' . $this->safeExtension($file, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                $file->move(public_path('images/products'), $filename);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                    'is_primary' => $i === 0,
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', "Product \"{$product->name}\" created.");
    }

    public function edit(Product $product): View
    {
        $categories = Category::active()->get();
        $product->load('images');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // AJAX set-primary-only request from image manager
        if ($request->boolean('_set_primary_only') || $request->input('_set_primary_only')) {
            $primaryId = $request->input('primary_image_id');
            if ($primaryId) {
                $product->images()->update(['is_primary' => false]);
                $product->images()->where('id', $primaryId)->update(['is_primary' => true]);
            }
            if ($request->expectsJson()) {
                return response()->json(['ok' => true]);
            }

            return back()->with('success', 'Primary image updated.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'sku' => "nullable|string|max:50|unique:products,sku,{$product->id}",
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'scent_notes' => 'nullable|string|max:300',
            'capacity_ml' => 'nullable|integer|min:0',
            'burn_time_hours' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'images' => 'nullable|array',
            'images.*' => 'image|max:4096',
        ]);

        $data['slug'] = $this->uniqueSlug(Str::slug($data['name']), $product->id);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        $product->update($data);

        if ($request->hasFile('images')) {
            $existingCount = $product->images()->count();
            foreach ($request->file('images') as $i => $file) {
                $filename = uniqid('prod_') . '.' . $this->safeExtension($file, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                $file->move(public_path('images/products'), $filename);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename,
                    'is_primary' => $existingCount === 0 && $i === 0,
                    'sort_order' => $existingCount + $i,
                ]);
            }
        }

        if ($primaryId = $request->primary_image_id) {
            $product->images()->update(['is_primary' => false]);
            $product->images()->where('id', $primaryId)->update(['is_primary' => true]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        if ($request->boolean('force_delete')) {
            foreach ($product->images as $image) {
                @unlink(public_path('images/products/'.basename($image->image_path)));
            }
            $name = $product->name;
            $product->forceDelete();

            return redirect()->route('admin.products.index')->with('success', "Product \"{$name}\" permanently deleted.");
        }

        $product->delete();

        return back()->with('success', "Product \"{$product->name}\" archived.");
    }

    public function toggle(int $product): RedirectResponse
    {
        $product = Product::withTrashed()->findOrFail($product);

        if ($product->trashed()) {
            $product->restore();

            return back()->with('success', "Product \"{$product->name}\" restored.");
        }

        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('success', $product->is_active ? 'Product activated.' : 'Product deactivated.');
    }

    public function show(Product $product): View
    {
        $product->load(['images', 'variants', 'reviews.user', 'category', 'orderItems']);

        return view('admin.products.show', compact('product'));
    }

    public function deleteImage(Request $request, ProductImage $image)
    {
        @unlink(public_path('images/products/'.basename($image->image_path)));
        $productId = $image->product_id;
        $image->delete();

        if (! ProductImage::where('product_id', $productId)->where('is_primary', true)->exists()) {
            ProductImage::where('product_id', $productId)->first()?->update(['is_primary' => true]);
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Image removed.');
    }

    private function uniqueSlug(string $base, ?int $excludeId = null): string
    {
        $slug = $base;
        $i = 1;
        while (Product::withTrashed()->where('slug', $slug)
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
