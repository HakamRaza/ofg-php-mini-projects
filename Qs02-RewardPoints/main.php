
<?php

use App\Controller\OrderController;
use App\Dto\OrderDTO;
use App\Enums\Currency;
use App\Helper\Conversion;
use App\Helper\Response;

require_once __DIR__ . './vendor/autoload.php';


// use App\Controller\OrderController;
// use App\Helper\Conversion;
// use App\Migration\DBInit;

// use App\Migration\DBInit;
// use App\Model\Currency;





// $init = new DBInit();
// $init->migrateTable();


// $conversion = new Conversion();
// $result = $conversion->convertIntToDecimal(1111);
// var_dump($result);

// $currency = new Currency();
// $result = $currency->findCurrency('MYR');
// var_dump($result['conversion_rate_usd']);

class Main
{
    // public function init()
    // {
    //     $init = new DBInit();
    //     $init->migrateTable();
    // }
    private $controller;
    private $response;
    private $conversion;

    public function __construct()
    {
        $this->controller = new OrderController();
        $this->response = new Response();
        $this->conversion = new Conversion();
    }

    public function add()
    {
        $dateTime = new DateTime();
        $newOrder = new OrderDTO();
        $newOrder->setCurrencyId(Currency::MYR->value());
        $newOrder->setTotalSales($this->conversion->convertDecimalToInt(167.89));
        $newOrder->setUserId(1);
        $newOrder->setUpdatedAt($dateTime);
        $newOrder->setCreatedAt($dateTime);
        $newOrder->setPointClaimed($this->conversion->convertDecimalToInt(90));

        try {
            $order = $this->controller->place($newOrder);
            return $this->response->sendResponse($order);
        } catch (\Throwable $th) {

            var_dump($th->getMessage());
            return $this->response->sendResponse('Failed to place order', 500);
        }
    }


    public function acceptOrder(int $orderId)
    {
    }
}

(new Main())->add();


?>