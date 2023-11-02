<?php

namespace App\Message;

final class ProcessTaskMessage
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */


     public function __construct(private int $orderId)
     {
         $this->orderId = $orderId;
     }

    public function getOrderId(): string
    {
        return $this->orderId;
    }
}
