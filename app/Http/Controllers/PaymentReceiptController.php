<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentReceiptRequest;
use App\Http\Requests\UpdatePaymentReceiptRequest;

use App\Models\PaymentReceipt;

class PaymentReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //if user is not authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        // list of payment receipts for user auth
        $paymentReceipts = PaymentReceipt::with(['business', 'user'])
            ->where('user_id', auth()->user()->id)
            ->get();
        //if no payment receipts found
        if ($paymentReceipts->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "No Payment Receipts found",
            ], 404);
        }
        return response()->json([
            "success" => true,
            "data" => $paymentReceipts,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentReceiptRequest $request)
    {
        //if user is not authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        // upload file
        if (!$request->hasFile('path_receipt')) {
            return response()->json([
                "success" => false,
                "message" => "File not found",
            ], 422);
        }
        $path = $request->file('path_receipt')->store('payment_receipts', 'public');
        // create payment receipt
        $paymentReceipt = PaymentReceipt::create([
            'business_id' => $request->business_id,
            'user_id' => auth()->user()->id,
            'path_receipt' => $path,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'status' => $request->status
        ]);
        return response()->json([
            "success" => true,
            "data" => $paymentReceipt,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentReceipt $paymentReceipt)
    {
        //if user is not authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        // show payment receipt
        $paymentReceipt->business = $paymentReceipt->business()->with(['user'])->first();
        $paymentReceipt->user = $paymentReceipt->user()->with(['user'])->first();
        return response()->json([
            "success" => true,
            "data" => $paymentReceipt,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentReceipt $paymentReceipt)
    {
        //if user is not authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        // delete payment receipt
        $paymentReceipt->delete();
        return response()->json([
            "success" => true,
            "message" => "Payment Receipt deleted successfully",
        ]);
    }
}
