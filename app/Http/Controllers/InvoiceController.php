<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Helper\SSLCommerz;
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
            // Read user from headers (your middleware sets these)
            $user_id = (int) $request->header('id');
            $user_email = (string) $request->header('email');

            if (!$user_id || !$user_email) {
                DB::rollBack();
                return ResponseHelper::Out('fail', 'Unauthorized / Missing user headers', 401);
            }

            $tran_id = uniqid();
            $delivery_status = 'Pending';
            $payment_status = 'Pending';

            //  Profile must exist
            $profile = CustomerProfile::where('user_id', $user_id)->first();
            if (!$profile) {
                DB::rollBack();
                return ResponseHelper::Out('fail', 'Customer profile not found', 404);
            }

            //  Cart must exist
            $cartList = ProductCart::where('user_id', $user_id)->get();
            if ($cartList->isEmpty()) {
                DB::rollBack();
                return ResponseHelper::Out('fail', 'Cart is empty', 400);
            }

            //  Build details (safe)
            $cus_details = "Name:$profile->cus_name,Address:$profile->cus_add,City:$profile->cus_city,Phone:$profile->cus_phone";
            $ship_details = "Name:$profile->ship_name,Address:$profile->ship_add,City:$profile->ship_city,Phone:$profile->ship_phone";

            // Total calculation (qty × price)
            $total = 0;
            foreach ($cartList as $item) {
                $qty = (int) ($item->qty ?? 1);
                $price = (float) ($item->price ?? 0);
                $total += ($qty * $price);
            }

            $vat = round(($total * 3) / 100, 2);
            $payable = round($total + $vat, 2);

            //  Create invoice
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

            foreach ($cartList as $item) {
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'product_id' => $item->product_id,
                    'user_id' => $user_id,
                    'qty' => (int) ($item->qty ?? 1),
                    'sale_price' => (float) ($item->price ?? 0),
                ]);
            }

            $paymentMethod = SSLCommerz::InitiatePayment($profile, $payable, $tran_id, $user_email);

            if (empty($paymentMethod)) {
                DB::rollBack();
                return ResponseHelper::Out('fail', 'No payment methods available (SSLCommerz not configured)', 400);
            }

              ProductCart::where('user_id', $user_id)->delete();

            DB::commit();

            return ResponseHelper::Out('success', [
                'paymentMethod' => $paymentMethod,
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

    public function InvoiceList(Request $request)
    {
        $user_id = (int) $request->header('id');
        if (!$user_id) return ResponseHelper::Out('fail', 'Unauthorized', 401);

        $data = Invoice::where('user_id', $user_id)->latest()->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function InvoiceProductList(Request $request)
    {
        $user_id = (int) $request->header('id');
        $invoice_id = (int) $request->invoice_id;

        if (!$user_id) return ResponseHelper::Out('fail', 'Unauthorized', 401);
        if (!$invoice_id) return ResponseHelper::Out('fail', 'Invoice id required', 422);

        $data = InvoiceProduct::where([
            'user_id' => $user_id,
            'invoice_id' => $invoice_id
        ])->with('product')->get();

        return ResponseHelper::Out('success', $data, 200);
    }

    public function PaymentSuccess(Request $request)
    {
        SSLCommerz::InitiateSuccess($request->query('tran_id'));
        return redirect('/profile');
    }

    public function PaymentCancel(Request $request)
    {
        SSLCommerz::InitiateCancel($request->query('tran_id'));
        return redirect('/profile');
    }

    public function PaymentFail(Request $request)
    {
        SSLCommerz::InitiateFail($request->query('tran_id'));
        return redirect('/profile');
    }

    public function PaymentIPN(Request $request)
    {
        return SSLCommerz::InitiateIPN(
            $request->input('tran_id'),
            $request->input('status'),
            $request->input('val_id')
        );
    }
}
