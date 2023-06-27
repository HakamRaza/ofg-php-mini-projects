<?php

namespace App\Model;

use App\DTO\OrderDTO;
use App\Enums\OrderStatus;
use App\Helper\DB;

class OrderSale
{
    public function __construct(
        protected DB $db
    ) {
    }

    public function create(OrderDTO $orderPayload)
    {
        $query = 'INSERT INTO `sales_order` (user_id, total_sales, currency_id, order_status_id) 
        VALUES (:userId, :totalSales, :currencyId, :orderStatusId );';

        $statement = $this->db->prepare($query);

        $statement->execute([
            "userId" => $orderPayload->userId,
            "totalSales" => $orderPayload->totalSales,
            "currencyId" => $orderPayload->currencyId,
            "orderStatusId" => OrderStatus::Pending
        ]);
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
