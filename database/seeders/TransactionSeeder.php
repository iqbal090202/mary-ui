<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaction = [
            'user_id' => 3,
            'total_price' => 1000000,
            'date' => now()
        ];

        $transactionDetail1 = [
            'transaction_id' => 1,
            'product_id' => 1,
            'price' => 500000,
            'qty' => 1
        ];
        $transactionDetail2 = [
            'transaction_id' => 1,
            'product_id' => 2,
            'price' => 500000,
            'qty' => 1
        ];

        Transaction::create($transaction);
        TransactionDetail::create($transactionDetail1);
        TransactionDetail::create($transactionDetail2);
    }
}
