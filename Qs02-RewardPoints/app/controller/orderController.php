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
    public function place(OrderDTO $orderPayload): OrderDTO
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

        // record point floating
        if ($pointClaimed > 0) {
            $this->reward->create($order->userId, TransactionType::Debit, $pointClaimed);
        }

        return $order;
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
    private function calculateEffectivePointClaim(int $totalSales, int $pointClaimed)
    {
    }
}
