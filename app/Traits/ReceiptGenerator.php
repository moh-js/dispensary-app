<?php
namespace App\Traits;

use App\Models\Order;

trait ReceiptGenerator
{
    /**
     * Generate the new receipt ID
     *
     * @param Order $order
     * @return string $receipt_id
     */
    public function generate($order, $type = null)
    {
        // format MYYDDMM{000000ID}

        $id = $order->id;
        $count = 1;
        $initialLimit = 9999;
        $finalLimit = $initialLimit;

        do { // Reset receipt id after it exceed six digits number
            $reset = false;

            if ($id > $initialLimit) { // check if id exceed limit
                $id = $order->id - $finalLimit;

                if ($id > $initialLimit) { // check if substracted id still exceed the limit number
                    $count = $count+1;
                    $finalLimit = $count*$initialLimit;
                    $reset = true;
                }
            } else {
                $id = $order->id;
            }
        } while ($reset);

        if ($type == 'invoice') {
            $code = 'MI';
        } else {
            $code = 'MR';
        }

        $date = now()->format('yz');
        $length = strlen($initialLimit);
        $string = substr(str_repeat(0, $length).$id, - $length);

        $receipt_id = $code.$date.$string;
        return $receipt_id;
    }
}
