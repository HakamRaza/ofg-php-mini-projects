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
                "totalSales" => (int) $this->conversion->convertDecimalToInt($orderPayload->totalSales),
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
        $orderDto->setPointClaimed($orderPayload->pointClaimed ?: 0);

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
            "totalSales" => (int) $this->conversion->convertDecimalToInt($orderPayload->totalSales),
            "currencyId" => $orderPayload->currencyId,
            "orderStatusId" => $orderPayload->orderStatusId
        ]);

        return $this->findFirst($orderPayload);
    }

    /**
     * Update order status
     */
    public function updateStatus(int $orderId, string $action): OrderDTO
    {
        $statusId = null;

        switch ($action) {
            case 'paid':
                $statusId = OrderStatus::InProgress->value();
            case 'cancel':
                $statusId = OrderStatus::Cancel->value();
                break;
            case 'complete':
                $statusId = OrderStatus::Complete->value();
                break;
            default:
                break;
        }

        if (!$statusId) return false;

        $query = 'UPDATE `sales_order` SET order_status_id = :statusId WHERE id = :orderId';

        $param = [
            "statusId" => $statusId,
            "orderId" => $orderId
        ];

        $statement = $this->db->prepare($query);
        $statement->execute($param);

        return $this->findFirst($orderId);
    }
}
