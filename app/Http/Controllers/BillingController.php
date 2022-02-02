<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Unit;
use App\Models\Order;
use App\Models\Patient;
use App\Models\Service;
use App\Models\OrderService;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use App\Traits\ReceiptGenerator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use  Meneses\LaravelMpdf\Facades\LaravelMpdf as PDF;

class BillingController extends Controller
{
    use ReceiptGenerator;

    public function patientBillPage(Patient $patient, $invoice_id = null)
    {
        $this->authorize('bill-view');

        if ($invoice_id) {
            $order = Order::where('invoice_id', $invoice_id)->first();
        } else {
            $order = $patient->getLastPendingOrder();
        }

        return view('patient.bill', [
            'patient' => $patient,
            'order' => $order
        ]);
    }

    public function addBillService(Request $request, Patient $patient, $invoice_id = null)
    {
        $this->authorize('bill-add');

        $service_name = strtolower((ServiceCategory::find($request->category)->name)??'');

        $validator = Validator::make($request->toArray(), [
            'unit' => ['required_if:category,1',],
            'category' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
            'service' => ['required', 'integer'],
        ], [
            'required_if' => ':attribute field is required when service category is '. $service_name
        ]);

        if($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error) {
                flash()->error($error[0]);
            }
            return back()->withInput();
        }

        // Retrieve order instance or create when not found
        if ($invoice_id) {
            $order = Order::where('invoice_id', $invoice_id)->first();
        }
        elseif($order = $patient->getLastPendingOrder()) {}
        else {
            $order = $patient->orders()->firstOrCreate([
                'patient_id' => $patient->id,
                'order_date' => now(),
                'cashier_id' => auth()->id(),
            ]);

            $order->update([
                'invoice_id' => $this->generate($order, 'invoice'),
            ]);
        }

        if ($order->status == 'completed') {
            flash()->error('Order already completed you cannot add bill service');
            return back();
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
            'service_category_id' => $request->category,
            'sub_total' => $service->price,
            'payment_type' => request('payment_type'),
            'total_price' => $service->price * $request->quantity,
            'quantity' => $request->quantity
        ]);

        flash('Service bill added successfully');
        return redirect()->route('bill.patient.page', [$patient->patient_id, $order->invoice_id]);
    }

    public function removeBillService(OrderService $billService)
    {
        $this->authorize('bill-remove-single');


        if ($billService->order->status == 'pending') {
            if ($billService->service_category_id == 1) {
                $relationship = 'prescriptions';
            } elseif($billService->service_category_id == 2) {
                $relationship = 'investigations';
            } else {
                $relationship = null;
            }

            if ($relationship) { // remove associated service

                $service = $billService->order->encounter->$relationship()->where('service_id', $billService->service_id)->first();

                if ($service) {
                    $service->delete();
                    flash('Bill Service deleted successfully');
                    return back();
                } else {
                    $billService->delete();
                    flash('Bill Service deleted successfully');
                }
            } else {
                $billService->delete();
                flash('Bill Service deleted successfully');
            }
        }
        else {
            flash()->error('Cannot delete this bill service');
        }

        return back();

    }

    public function completeBill($invoice_id)
    {
        $this->authorize('bill-complete');

        $order = Order::where('invoice_id', $invoice_id)->first();

        // 1 = Medicine
        foreach ($order->items as $orderService) {
            if (!$orderService->payment_type) { // check if payment type is present
                flash()->error('Add payment type to '.$orderService->service->name);
                return back();
            }

            if ($orderService->service->item->countable??false) { // check if item is countable
                $unitItem = $orderService->service->item->getUnitById($orderService->unit_id);

                if ($unitItem->remain < $orderService->quantity) {
                    flash()->error('The quantity requested is greater than the available amount in the inventory - '.$orderService->service->name);
                    return back();
                }
            } else {
                if (($orderService->service->item->inventoryCategory->id??false) == 1) {
                    flash()->error($orderService->service->item->name.' should be a countable item');
                    return back();
                }
            }
        }

        $order->update([
            'total_price' => $order->items()->sum('total_price'),
            'receipt_id' => $this->generate($order),
            'cashier_id' => auth()->id(),
            'status' => 'completed',
            'created_at' => now()
        ]);

        foreach ($order->items as $orderService) {
            // forget session created when entering payment type manualy if is found
            $sessionName = $orderService->order->patient->patient_id.'session'.$orderService->id;
            if (session()->has($sessionName)) {
                session()->forget($sessionName);
            }

            if ($orderService->service->item->countable??false) {
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
        $this->authorize('bill-completed');

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
        'format' => [80,$pageHeigt+10],
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
