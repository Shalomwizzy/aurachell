<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Preorder;
use Illuminate\Http\Request;

class PreorderController extends Controller
{
    public function index(Request $request)
    {
        $query = Preorder::with(['product.primaryImage'])->latest();

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $preorders = $query->paginate(20)->withQueryString();
        $counts = [
            'all' => Preorder::count(),
            'pending' => Preorder::where('status', 'pending')->count(),
            'contacted' => Preorder::where('status', 'contacted')->count(),
            'fulfilled' => Preorder::where('status', 'fulfilled')->count(),
            'cancelled' => Preorder::where('status', 'cancelled')->count(),
        ];

        return view('admin.preorders.index', compact('preorders', 'counts'));
    }

    public function update(Request $request, Preorder $preorder)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,contacted,fulfilled,cancelled',
        ]);

        $preorder->update($data);

        return back()->with('success', 'Pre-order updated.');
    }

    public function destroy(Preorder $preorder)
    {
        $preorder->delete();

        return back()->with('success', 'Pre-order deleted.');
    }
}
