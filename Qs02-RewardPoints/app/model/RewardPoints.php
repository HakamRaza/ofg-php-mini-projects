<?php

namespace App\Model;

use App\Enums\TransactionType;
use App\Helper\DB;

class RewardPoints
{
    protected $db;
    protected $conversion;
    private $tableName = 'point_rewards';

    public function __construct()
    {
        $this->db = new DB();
    }

    /**
     * Get nett reward points value
     */
    public function available()
    {
        $query = 'SELECT SUM(
            CASE 
                WHEN transaction_type_id = :creditId THEN points 
                WHEN transaction_type_id = :debitId THEN -points 
                ELSE +0
            END
        ) AS available
        from `' . $this->tableName . '` WHERE expired_at > NOW();';

        $statement = $this->db->prepare($query);

        $statement->execute([
            'creditId' => TransactionType::Credit->value(),
            'debitId' => TransactionType::Debit->value(),
        ]);

        return (int) $statement->fetch()["available"];
    }

    /**
     * Add reward point transaction records
     * 
     */
    public function create(int $userId, int $orderId, int $transactionTypeId, int $pointClaim): bool
    {
        $query = 'INSERT INTO `' . $this->tableName . '` (user_id, sales_order_id, transaction_type_id, points, expired_at, created_at) 
        VALUES (:userId, :orderId, :transactionTypeId, :points, DATE_ADD(NOW(), INTERVAL 1 YEAR), NOW());';

        $statement = $this->db->prepare($query);

        return $statement->execute([
            "userId" => $userId,
            "orderId" => $orderId,
            "transactionTypeId" => $transactionTypeId,
            "points" => $pointClaim
        ]);
    }

    /**
     * Delete records
     * 
     */
    public function destroyOrderRecord(int $orderId): bool
    {
        $query = 'DELETE FROM `' . $this->tableName . '` WHERE sales_order_id = :orderId;';

        $statement = $this->db->prepare($query);

        return $statement->execute([
            "orderId" => $orderId
        ]);
    }
}
