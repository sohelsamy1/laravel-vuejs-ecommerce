<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\CustomerProfile;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\ProductCart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{

public function InvoiceCreate(Request $request)
{
    DB::beginTransaction();

    try {
        // Read user from headers
        $user_id = (int) $request->header('id');
        $user_email = (string) $request->header('email'); // optional now

        if (!$user_id) {
            DB::rollBack();
            return ResponseHelper::Out('fail', 'Unauthorized / Missing user headers', 401);
        }

        $tran_id = uniqid();
        $delivery_status = 'Pending';
        $payment_status = 'Pending';

        // Profile must exist
        $profile = CustomerProfile::where('user_id', $user_id)->first();
        if (!$profile) {
            DB::rollBack();
            return ResponseHelper::Out('fail', 'Customer profile not found', 404);
        }

        // Cart must exist
        $cartList = ProductCart::where('user_id', $user_id)->get();
        if ($cartList->isEmpty()) {
            DB::rollBack();
            return ResponseHelper::Out('fail', 'Cart is empty', 400);
        }

        // Build customer & shipping details
        $cus_details = "Name:$profile->cus_name,Address:$profile->cus_add,City:$profile->cus_city,Phone:$profile->cus_phone";
        $ship_details = "Name:$profile->ship_name,Address:$profile->ship_add,City:$profile->ship_city,Phone:$profile->ship_phone";

        // Total calculation
        $total = 0;
        foreach ($cartList as $item) {
            $qty = (int) ($item->qty ?? 1);
            $price = (float) ($item->price ?? 0);
            $total += ($qty * $price);
        }

        $vat = round(($total * 3) / 100, 2);
        $payable = round($total + $vat, 2);

        // Create invoice
        $invoice = Invoice::create([
            'total' => $total,
            'vat' => $vat,
            'payable' => $payable,
            'cus_details' => $cus_details,
            'ship_details' => $ship_details,
            'tran_id' => $tran_id,
            'delivery_status' => $delivery_status,
            'payment_status' => $payment_status,
            'user_id' => $user_id,
        ]);

        $invoiceID = $invoice->id;

        // Add products to invoice
        foreach ($cartList as $item) {
            InvoiceProduct::create([
                'invoice_id' => $invoiceID,
                'product_id' => $item->product_id,
                'user_id' => $user_id,
                'qty' => (int) ($item->qty ?? 1),
                'sale_price' => (float) ($item->price ?? 0),
            ]);
        }

        // Clear cart
        ProductCart::where('user_id', $user_id)->delete();

        DB::commit();

        return ResponseHelper::Out('success', [
            'tran_id' => $tran_id,
            'invoice_id' => $invoiceID,
            'payable' => $payable,
            'vat' => $vat,
            'total' => $total,
        ], 200);

    } catch (Exception $e) {
        DB::rollBack();
        return ResponseHelper::Out('fail', $e->getMessage(), 500);
    }
 }

}
