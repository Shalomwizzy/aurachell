<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingController extends Controller
{
    public function index(): View
    {
        $zones = ShippingZone::with('rates')->orderBy('sort_order')->get();

        return view('admin.shipping.index', compact('zones'));
    }

    public function create(): View
    {
        return view('admin.shipping.form', ['zone' => new ShippingZone]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $zone = ShippingZone::create($data['zone']);
        $this->syncRates($zone, $data['rates']);

        return redirect()->route('admin.shipping.index')->with('success', 'Shipping zone created.');
    }

    public function edit(ShippingZone $shipping): View
    {
        $shipping->load('rates');

        return view('admin.shipping.form', ['zone' => $shipping]);
    }

    public function update(Request $request, ShippingZone $shipping): RedirectResponse
    {
        $data = $this->validated($request);
        $shipping->update($data['zone']);
        $this->syncRates($shipping, $data['rates']);

        return redirect()->route('admin.shipping.index')->with('success', 'Shipping zone updated.');
    }

    public function destroy(ShippingZone $shipping): RedirectResponse
    {
        $shipping->delete();

        return redirect()->route('admin.shipping.index')->with('success', 'Zone deleted.');
    }

    public function toggle(ShippingZone $shipping): RedirectResponse
    {
        $shipping->update(['is_active' => ! $shipping->is_active]);

        return back()->with('success', 'Zone '.($shipping->is_active ? 'activated' : 'deactivated').'.');
    }

    private function validated(Request $request): array
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'states' => 'required|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'standard_price' => 'required|numeric|min:0',
            'standard_min_days' => 'required|integer|min:1',
            'standard_max_days' => 'required|integer|min:1',
            'express_price' => 'required|numeric|min:0',
            'express_min_days' => 'required|integer|min:1',
            'express_max_days' => 'required|integer|min:1',
        ]);

        $states = array_values(array_filter(array_map('trim', explode(',', $request->states))));

        return [
            'zone' => [
                'name' => $request->name,
                'states' => $states,
                'is_active' => $request->boolean('is_active', true),
                'sort_order' => (int) $request->sort_order,
            ],
            'rates' => [
                ['method' => 'standard', 'price' => $request->standard_price, 'min_days' => $request->standard_min_days, 'max_days' => $request->standard_max_days],
                ['method' => 'express',  'price' => $request->express_price,  'min_days' => $request->express_min_days,  'max_days' => $request->express_max_days],
            ],
        ];
    }

    private function syncRates(ShippingZone $zone, array $rates): void
    {
        foreach ($rates as $rate) {
            $zone->rates()->updateOrCreate(['method' => $rate['method']], $rate);
        }
    }
}
