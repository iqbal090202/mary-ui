<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function index(Request $request)
    {
        $query = Product::query();
        $limit = $request->query('limit', 3);
        $sortBy = $request->query('sort_by', 'updated_at');
        $direction = $request->query('direction', 'desc');

        if ($request->has('product_name')) {
            $query->where('product_name', 'like', '%' . $request->input('product_name') . '%');
        }

        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->query('price_min'));
        }

        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->query('price_max'));
        }

        $products = $query->with('variants')
            ->orderBy($sortBy, $direction)
            ->paginate($limit);

        return $this->sendResponse([
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
            ],
        ], 'Products retrieved successfully.');
    }

    public function show($id)
    {
        $product = Product::with('variants')->find($id);

        if (!$product) {
            return $this->sendError([], 'Product no found.', 404);
        }

        return $this->sendResponse($product, 'Product retrieved successfully.');
    }
}
