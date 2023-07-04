
<?php

use App\Controller\OrderController;
use App\Dto\OrderDTO;
use App\Helper\Conversion;
use App\Migration\DBInit;

require_once __DIR__ . './vendor/autoload.php';

class Main
{
    private $controller;
    private $conversion;

    public function __construct()
    {
        $this->controller = new OrderController();
        $this->conversion = new Conversion();
    }

    public function initDB()
    {
        $init = new DBInit();
        $init->migrateTable();
    }

    public function placeOrder(
        int $userId,
        int $currencyId,
        float $amountOrder,
        float $amountPointClaim = 0.00,
    ) {
        $dateTime = new DateTime();
        $newOrder = new OrderDTO();
        $newOrder->setCurrencyId($currencyId);
        $newOrder->setTotalSales($this->conversion->convertDecimalToInt($amountOrder));
        $newOrder->setUserId($userId);
        $newOrder->setUpdatedAt($dateTime);
        $newOrder->setCreatedAt($dateTime);
        $newOrder->setPointClaimed($this->conversion->convertDecimalToInt($amountPointClaim));

        return $this->controller->place($newOrder);
    }

    public function cancelOrder(int $orderId)
    {
        return $this->controller->cancel($orderId);
    }

    public function payOrder(int $orderId)
    {
        return $this->controller->paid($orderId);
    }

    public function deliveredOrder(int $orderId)
    {
        return $this->controller->complete($orderId);
    }
}

// (new Main())->placeOrder(1, 2, 100.00, 10.00);
// (new Main())->deliveredOrder(1);


?>