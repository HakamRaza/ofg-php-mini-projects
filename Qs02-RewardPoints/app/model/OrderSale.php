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

            // reuse previous dto
            $orderDto = $orderPayload;
        } else {
            $query = 'SELECT * FROM `' . $this->tableName . '` 
            WHERE id = :orderId 
            LIMIT 1;';

            $param = [
                "orderId" => $orderPayload,
            ];

            $orderDto = new OrderDTO();
        }

        $statement = $this->db->prepare($query);
        $statement->execute($param);
        $order = $statement->fetch() ?: [];

        if (count($order) == 0) {
            return null;
        }

        $orderDto->updateFromQuery($order);

        return $orderDto;
    }

    public function create(OrderDTO $orderPayload)
    {
        // $query = 'INSERT INTO `' . $this->tableName . '` (user_id, total_sales, currency_id, order_status_id) 
        // VALUES (:userId, :totalSales, :currencyId, :orderStatusId);';

        // $statement = $this->db->prepare($query);

        // $statement->execute([
        //     "userId" => $orderPayload->userId,
        //     "totalSales" => (int) $this->conversion->convertDecimalToInt($orderPayload->totalSales),
        //     "currencyId" => $orderPayload->currencyId,
        //     "orderStatusId" => $orderPayload->orderStatusId
        // ]);

        return $this->findFirst(14);
    }

    public function update(OrderDTO $orderPayload, string $action)
    {
        $query = "";
        $param = [];

        switch ($action) {
            case 'paid':
                $query = 'UPDATE `sales_order` SET order_status_id = :statusId WHERE id = :orderId';
                $param = [
                    "statusId" => OrderStatus::Complete,
                    "orderId" => $orderPayload->id
                ];

                break;

            default:
                # code...
                break;
        }

        $statement = $this->db->prepare($query);

        $statement->execute($param);
    }
}
