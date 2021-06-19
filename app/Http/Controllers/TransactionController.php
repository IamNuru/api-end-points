<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
/* use Illuminate\Support\Facades\Auth; */

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = auth()->user()->id;
        $data = User::with('transactions.orders.products')->where('id', $uid)->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'payment_method' => 'required|string',
        ]);


        DB::transaction(function () use ($request) {
            $transaction = auth()->user()->transactions()->create([
                'transaction_id' => $request->transaction_id,
                'products' => $request->cart,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'status' => 'placed',
            ]);
            foreach ($request->cart as $item) {

                $order = auth()->user()->orders()->create(array(
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                ));
                $product = Product::find($item['id']);
                if ($product->qty > $item['qty']) {
                    $product->qty = $product->qty - $item['qty'];
                    $product->update();
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'error',
                        'errors' => ['Quantity requested is not available'],
                    ], 401);
                }
            };
        });



        return response()->json(['message' => 'Order successfuly placed']);
    }

    public function checkOrderStatus($orderNumber){
        $order = Transaction::where('transaction_id' , $orderNumber)->first();

        if ($order) {
            return response()->json($order);
        } else {
            return response()->json('Not Found');
        }
        

        

    }


    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
