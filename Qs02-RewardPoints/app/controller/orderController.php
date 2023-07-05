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
     * 
     */
    public function get(int $userId)
    {
        $orders = $this->order->getUserOrder($userId);

        return $this->response->sendResponse($orders, 200);
    }

    /**
     * Registed new order
     */
    public function place(OrderDTO $orderPayload): array
    {
        // any point claim ?
        $pointClaimed = $orderPayload->pointClaimed;

        if ($pointClaimed > 0) {
            // any point claim ?
            $pointConverted = $this->conversion->convertPointClaimToPointDBRate($orderPayload->currencyId, $orderPayload->pointClaimed);

            if (!$pointConverted) {
                return $this->response->sendResponse('Currency not recognize', 422);
            }
            $currentPoint = $this->reward->available();

            if ($currentPoint < $pointConverted) {
                return $this->response->sendResponse('Reward points not enough to be claimed.', 422);
            }
        }

        // update status and save order
        $orderPayload->setOrderStatusId(OrderStatus::Pending->value());

        $order = $this->order->create($orderPayload);

        // record point claim
        if ($pointClaimed > 0) {
            $this->reward->create($order->userId, $order->id, TransactionType::Debit->value(), $pointClaimed);
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
        $order = $this->order->updateStatus($orderId, 'cancel');

        if (!$order) {
            return $this->response->sendResponse('Invalid request', 409);
        }

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

        if (!$order) {
            return $this->response->sendResponse('Invalid request', 409);
        }

        return $this->response->sendResponse($order, 200);
    }

    public function complete(int $orderId)
    {
        $order = $this->order->findFirst($orderId);

        if (!$order) {
            $this->response->sendResponse('Order not found', 404);
        }

        // update order status
        $order = $this->order->updateStatus($orderId, 'complete');

        if (!$order) {
            return $this->response->sendResponse('Invalid request', 409);
        }

        // give points based on currency
        $rewardPt = $this->conversion->convertPaymentToPointDBRate($order->currencyId, $order->totalSales);
        $this->reward->create($order->userId, $order->id, TransactionType::Credit->value(), $rewardPt);

        return $this->response->sendResponse($order, 200);
    }
}
