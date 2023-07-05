<?php

namespace App\Model;

use App\DTO\OrderDTO;
use App\Enums\OrderStatus;
use App\Helper\Conversion;
use App\Helper\DB;

class OrderSale
{
    protected $db;
    protected $conversion;
    private $tableName = 'sales_order';

    public function __construct()
    {
        $this->db = new DB();
        $this->conversion = new Conversion();
    }

    /**
     * Get all order belong to user
     */
    public function getUserOrder(int $userId)
    {
        $query = 'SELECT * FROM `' . $this->tableName . '` WHERE user_id = :userId;';
        $statement = $this->db->prepare($query);
        $statement->execute([
            "userId" => $userId
        ]);

        return $statement->fetchAll() ?: [];
    }

    /**
     * Find order
     */
    public function findFirst(int|OrderDTO $orderPayload)
    {
        if ($orderPayload instanceof OrderDTO) {
            $query = 'SELECT * FROM `' . $this->tableName . '` 
                WHERE user_id = :userId 
                AND total_sales = :totalSales 
                AND created_at = FROM_UNIXTIME(:createdAt)
                ORDER BY id DESC
                LIMIT 1;';

            $param = [
                "userId" => $orderPayload->userId,
                "createdAt" => $orderPayload->createdAt,
                "totalSales" => $orderPayload->totalSales,
            ];
        } else {
            $query = 'SELECT * FROM `' . $this->tableName . '` 
            WHERE id = :orderId 
            LIMIT 1;';

            $param = [
                "orderId" => $orderPayload,
            ];
        }

        $statement = $this->db->prepare($query);
        $statement->execute($param);
        $order = $statement->fetch() ?: [];

        if (count($order) == 0) {
            return null;
        }

        $orderDto = new OrderDTO();
        $orderDto->updateFromQuery($order);
        $orderDto->setPointClaimed($orderPayload instanceof OrderDTO ? $orderPayload->pointClaimed : 0);

        return $orderDto;
    }

    /**
     * Insert order record
     */
    public function create(OrderDTO $orderPayload): OrderDTO
    {
        $query = 'INSERT INTO `' . $this->tableName . '` (user_id, total_sales, currency_id, order_status_id) 
        VALUES (:userId, :totalSales, :currencyId, :orderStatusId);';

        $statement = $this->db->prepare($query);

        $statement->execute([
            "userId" => $orderPayload->userId,
            "totalSales" => $orderPayload->totalSales,
            "currencyId" => $orderPayload->currencyId,
            "orderStatusId" => $orderPayload->orderStatusId
        ]);

        return $this->findFirst($orderPayload);
    }

    /**
     * Update order status
     */
    public function updateStatus(int $orderId, string $action): OrderDTO|false
    {
        $query = "UPDATE `sales_order` SET order_status_id = :updatedStatus 
            WHERE id = :orderId AND order_status_id = :currentStatus;";

        $param = null;

        switch ($action) {
            case 'paid':
                // can only paid if order in 'pending'
                $param = [
                    "orderId" => $orderId,
                    "updatedStatus" => OrderStatus::InProgress->value(),
                    "currentStatus" => OrderStatus::Pending->value()
                ];
                break;
            case 'cancel':
                // can only cancel order in 'pending'
                $param = [
                    "orderId" => $orderId,
                    "updatedStatus" => OrderStatus::Cancel->value(),
                    "currentStatus" => OrderStatus::Pending->value()
                ];
                break;
            case 'complete':
                // can only complete if order 'in progress'
                $param = [
                    "orderId" => $orderId,
                    "updatedStatus" => OrderStatus::Complete->value(),
                    "currentStatus" => OrderStatus::InProgress->value()
                ];
                break;
            default:
                break;
        }

        if (!$param) return false;

        $statement = $this->db->prepare($query);
        $statement->execute($param);

        return $this->findFirst($orderId);
    }
}
