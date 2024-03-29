
<?php

use App\Controller\OrderController;
use App\Dto\OrderDTO;
use App\Helper\Conversion;
use App\Helper\Response;
use App\Migration\Init;
use App\Model\RewardPoints;

class Main
{
    private $controller;
    private $conversion;
    private $response;
    private $reward;

    public function __construct()
    {
        $this->controller = new OrderController();
        $this->conversion = new Conversion();
        $this->response = new Response();
        $this->reward = new RewardPoints();
    }

    public function initDB()
    {
        $init = new Init();

        try {
            $init->migrateTable();

            return $this->response->sendResponse('Successfully reset DB');
        } catch (\Throwable $th) {
            return $this->response->sendResponse('Failed to reset DB', 500);
        }
    }

    public function placeOrder(
        int $userId,
        int $currencyId,
        float $amountOrder,
        float $amountPointClaim = 0.00
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

    public function getPointLeft()
    {
        return $this->reward->available();
    }

    public function getOrderList(int|string $userId)
    {
        return $this->controller->get(intval($userId));
    }
}

// (new Main())->initDB();
// (new Main())->placeOrder(1, 1, 200.00, 0.30);
// (new Main())->placeOrder(1, 2, 130.00, 0.00);
// (new Main())->cancelOrder(4);
// (new Main())->cancelOrder(1);
// (new Main())->payOrder(2);
// (new Main())->deliveredOrder(2);


?>