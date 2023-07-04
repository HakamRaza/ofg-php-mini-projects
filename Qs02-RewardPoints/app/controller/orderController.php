<?php

namespace App\Controller;

use App\DTO\OrderDTO;
use App\Enums\OrderStatus;
use App\Enums\TransactionType;
use App\Helper\Response;
use App\Model\RewardPoints;
use App\Model\OrderSale;
use Exception;

/**
 * 
 * @method orderPlace
 * @method orderPaid
 * @method orderDelivered
 */
class OrderController
{
    protected $order;
    protected $reward;
    protected $response;

    public function __construct()
    {
        $this->order = new OrderSale();
        $this->reward = new RewardPoints();
        $this->response = new Response();
    }

    /**
     * Registed new order
     */
    public function orderPlace(OrderDTO $orderPayload)
    {
        // any point claim ?
        $pointClaimed = $orderPayload->pointClaimed;
        
        // check existing point
        if ($pointClaimed > 0) {
            $currentPoint = $this->reward->available();

            if ($currentPoint < $pointClaimed) {
                throw new Exception('Reward points not enough to be claimed.');
            }
        }

        // update status save order
        $orderPayload->setOrderStatusId(OrderStatus::Pending->value());
        $order = $this->order->create($orderPayload);
        var_dump($order);
        die();



        // else {
        //     $this->reward->create($orderPayload->userId, TransactionType::Debit, $pointClaimed);
        // }
    }


    public function cancel(OrderDTO $orderPayload)
    {
        // calculate points

        // assign points

        //
    }

    public function paid(OrderDTO $orderPayload)
    {
        // calculate points

        // assign points

        //
    }

    public function complete(OrderDTO $orderPayload)
    {
    }

    /**
     * Get effective debit/credit point to be claim for each order
     */
    private function calculateEffectivePointClaim(float $totalSales, float $pointClaimed)
    {
    }
}
