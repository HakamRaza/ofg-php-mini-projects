<?php

namespace App\Model;

use App\Helper\DB;

class Currency
{
    protected $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    /**
     * Find currency detail
     * 
     * @return array|null $query
     */
    public function findCurrency(int $currencyId)
    {
        $query = 'SELECT * FROM `currency` WHERE id = :currencyId';

        $statement = $this->db->prepare($query);

        $statement->execute([
            "currencyId" => $currencyId,
        ]);

        return $statement->fetch() ?: null;
    }
}
