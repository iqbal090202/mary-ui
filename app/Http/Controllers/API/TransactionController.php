<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends BaseController
{
    public function index()
    {
        $transactions = Transaction::with('details.product', 'details.variant')->where('user_id', Auth::id())->paginate(10);

        return $this->sendResponse([
            'transactions' => $transactions->items(),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'next_page_url' => $transactions->nextPageUrl(),
                'prev_page_url' => $transactions->previousPageUrl(),
                'total' => $transactions->total(),
                'per_page' => $transactions->perPage(),
            ],
        ], 'Transactions retrieved successfully.');
    }

    public function show($id)
    {
        $transaction = Transaction::with('details.product', 'details.variant')->find($id);

        if (!$transaction) {
            return $this->sendError([], 'Transaction no found.', 404);
        }

        return $this->sendResponse($transaction, 'Transaction retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.variant_id' => 'nullable|exists:product_variants,id',
            'details.*.qty' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'date' => now(),
            ]);

            $totalPrice = 0;

            foreach ($validatedData['details'] as $detail) {
                $product = Product::find($detail['product_id']);
                $price = $product->price;

                if (isset($detail['variant_id'])) {
                    $variant = ProductVariant::find($detail['variant_id']);

                    if ($variant->stock < $detail['qty']) {
                        return $this->sendError([], "Stock for variant {$variant->name} is insufficient.");
                    }

                    $price = $variant->price;
                    $variant->decrement('stock', $detail['qty']);
                } else {
                    if ($product->stock < $detail['qty']) {
                        return $this->sendError([], "Product for variant {$product->name} is insufficient.");
                    }

                    $product->decrement('stock', $detail['qty']);
                }

                $totalPrice += $price * $detail['qty'];

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'variant_id' => $detail['variant_id'] ?? null,
                    'price' => $price * $detail['qty'],
                    'qty' => $detail['qty']
                ]);
            }

            $transaction->update(['total_price' => $totalPrice]);

            DB::commit();

            return $this->sendResponse($transaction->load('details.product', 'details.variant'), 'Transaction created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage(), 'Transaction creation failed.');
        }
    }
}
