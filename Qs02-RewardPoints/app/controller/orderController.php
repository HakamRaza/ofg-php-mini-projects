<?php

namespace App\Controller;

use App\DTO\OrderDTO;
use App\Model\RewardPoints;
use App\Model\OrderSale;

/**
 * 
 * @method placeOrder
 */
class OrderController
{
    public function __construct(
        protected OrderSale $order,
        protected RewardPoints $reward
    ) {
    }



    /**
     * 
     */
    public function create(OrderDTO $orderPayload)
    {
        // save order




        // calculate points


        // assign points


        //
    }

    public function update(OrderDTO $orderPayload)
    {
    }
}
