<?php

namespace App\Controller;

use App\DTO\OrderDTO;
use App\Enums\OrderStatus;
use App\Enums\TransactionType;
use App\Helper\Conversion;
use App\Helper\Response;
use App\Model\RewardPoints;
use App\Model\OrderSale;

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
    protected $conversion;

    public function __construct()
    {
        $this->order = new OrderSale();
        $this->reward = new RewardPoints();
        $this->response = new Response();
        $this->conversion = new Conversion();
    }

    /**
     * Registed new order
     */
    public function place(OrderDTO $orderPayload): array
    {
        // any point claim ?
        $pointClaimed = $this->conversion->convertPointClaimToPointDBRate($orderPayload->currencyId, $orderPayload->pointClaimed);

        if (!$pointClaimed) {
            return $this->response->sendResponse('Currency not recognize', 422);
        }

        // check existing point enough
        if ($pointClaimed > 0) {
            $currentPoint = $this->reward->available();

            if ($currentPoint < $pointClaimed) {
                return $this->response->sendResponse('Reward points not enough to be claimed.', 422);
            }
        }

        // update status and save order
        $orderPayload->setOrderStatusId(OrderStatus::Pending->value());
        $order = $this->order->create($orderPayload);

        // record point claim
        if ($pointClaimed > 0) {
            $this->reward->create($order->userId, TransactionType::Debit, $pointClaimed);
        }

        return $this->response->sendResponse($order);
    }


    public function cancel(int $orderId)
    {
        $order = $this->order->findFirst($orderId);

        if (!$order) {
            $this->response->sendResponse('Order not found', 404);
        }

        // update order status
        $this->order->updateStatus($orderId, 'cancel');

        // reset reward points used
        $this->reward->destroyOrderRecord($orderId);

        return $this->response->sendResponse('Order cancelled', 201);
    }

    public function paid(int $orderId)
    {
        $order = $this->order->findFirst($orderId);

        if (!$order) {
            $this->response->sendResponse('Order not found', 404);
        }

        // update order status
        $order = $this->order->updateStatus($orderId, 'paid');

        return $this->response->sendResponse($order, 201);
    }

    public function complete(int $orderId)
    {
        $order = $this->order->findFirst($orderId);

        if (!$order) {
            $this->response->sendResponse('Order not found', 404);
        }

        // update order status
        $order = $this->order->updateStatus($orderId, 'complete');

        // give points based on currency
        $rewardPt = $this->conversion->convertPaymentToPointDBRate($order->currencyId, $order->totalSales);
        $this->reward->create($order->userId, TransactionType::Credit, $rewardPt);

        return $this->response->sendResponse($order, 201);
    }
}
