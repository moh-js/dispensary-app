<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Order;
use App\Models\Patient;
use App\Models\Service;
use App\Models\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use  Meneses\LaravelMpdf\Facades\LaravelMpdf as PDF;

class BillingController extends Controller
{
    public function patientBillPage(Patient $patient, $invoice_id = null)
    {
        $order = Order::where('invoice_id', $invoice_id)->first();

        return view('patient.bill', [
            'patient' => $patient,
            'order' => $order
        ]);
    }

    public function addBillService(Request $request, Patient $patient, $invoice_id = null)
    {
        // $request->validate([]);

        // Retrieve order instance or create when not found
        if ($invoice_id) {
            $order = Order::where('invoice_id', $invoice_id)->first();
        } else {
            $order = $patient->orders()->firstOrCreate([
                'invoice_id' => rand(1,23232),
                'patient_id' => $patient->id
            ], [
                'order_date' => now(),
                'cashier_id' => auth()->id(),
            ]);
        }

        $service = Service::find($request->service);
        if ($request->category == 1) {
            $unitItem = $service->item->getUnitById($request->unit);

            if ($unitItem->remain < $request->quantity) {
                flash()->error('The quantity requested is greater than the available amount in the inventory - '.$service->name);
                return back();
            }
        }

        // Add bill service to order
        $order->items()->firstOrCreate([
            'service_id' => $service->id,
            'unit_id' => $request->unit,
            'service_category_id' => $request->category
        ], [
            'sub_total' => $service->price,
            'total_price' => $service->price * $request->quantity,
            'quantity' => $request->quantity
        ]);

        flash('Service bill added successfully');
        return redirect()->route('bill.patient.page', [$patient->patient_id, $order->invoice_id]);
    }

    public function removeBillService(OrderService $billService)
    {
        if ($billService->order->status == 'pending') {
            $billService->delete();
            flash('Bill Service deleted successfully');
        } else {
            flash()->error('Cannot delete this bill service');
        }

        return back();

    }

    public function completeBill($invoice_id)
    {
        $order = Order::where('invoice_id', $invoice_id)->first();

        foreach ($order->items as $orderService) {
            if ($orderService->service_category_id == 1) {
                $unitItem = $orderService->service->item->getUnitById($orderService->unit_id);

                if ($unitItem->remain < $orderService->quantity) {
                    flash()->error('The quantity requested is greater than the available amount in the inventory - '.$orderService->service->name);
                    return back();
                }
            }
        }

        $order->update([
            'total_price' => $order->items()->sum('total_price'),
            'receipt_id' => rand(1212,21212121),
            'cashier_id' => auth()->id(),
            'status' => 'completed',
            'payment_type' => request()->payment_mode
        ]);

        foreach ($order->items as $orderService) {
            if ($orderService->service_category_id == 1) {
                $unitItem = $orderService->service->item->getUnitById($orderService->unit_id);

                if ($unitItem->remain >= $orderService->quantity) {
                    $unitItem->update([
                        'remain' => $unitItem->remain - $orderService->quantity
                    ]);
                }
            }
        }

        $this->generateReceipt($order); // Generate order receipt

        flash('Order completed successfully');
        return redirect()->route('bill.completed', $order->receipt_id);
    }

    public function completedBill($receipt_id)
    {
        $order = Order::where('receipt_id', $receipt_id)->first();

        if (!is_file(public_path($order->receipt_file))) {
            $this->generateReceipt($order);
        }

        $receiptPath = asset($order->receipt_file);

        return view('patient.complete-bill', [
            'receiptPath' => $receiptPath,
            'order' => $order
        ]);

    }

    public function generateReceipt($order)
    {
        // Calculate the receipt height
        $mpdf=new mPDF();
        $mpdf->WriteHTML(View::make('invoice', ['order' => $order], [])->render());
        $pageHeigt = $mpdf->y;
        unset($mpdf);

        // Generate PDF for Receipt
        $pdf = PDF::loadView('invoice', ['order' => $order] ,[] ,[
        'format' => [80,$pageHeigt],
        'default_font_size' => '10'
        ]);

        // Save receipt
        $fileName = now()->format('Y-m-d-H-i').rand(112,21213).'.pdf';
        $pdf->save(storage_path('app/public/receipts/'.$fileName));

        // Update receipt saved Path
        $order->update([
            'receipt_file' => 'storage/receipts/'.$fileName,
        ]);
    }
}
