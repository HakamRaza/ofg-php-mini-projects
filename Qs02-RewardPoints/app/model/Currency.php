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

    public function findCurrency(string $currencyCode)
    {
        $query = 'SELECT * FROM `currency` WHERE currency_code = :currencyCode';

        $statement = $this->db->prepare($query);

        $statement->execute([
            "currencyCode" => $currencyCode,
        ]);

        return $statement->fetch() ?: [];
    }
}
