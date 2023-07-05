<!DOCTYPE HTML>
<html>

<head>
    <title>Question Two</title>
    <style>
        .error {
            color: #FF0000;
        }
    </style>
</head>

<body>
    <?php
    require_once './vendor/autoload.php';
    require_once './app/main.php';

    $USER_ID = 1;
    $main = new Main();
    $message = '';
    $pointLeft = 0;
    $orderList = [];
    $amountSale = $amountClaim = "0.00";
    $currencyId = null;
    $orderPointer;

    // GET AMOUNT OF POINT
    $pointLeft = $main->getPointLeft();

    // GET LIST OF ORDER
    $listRes = $main->getOrderList($USER_ID);
    $orderList = $listRes['data'];

    if ($_SERVER["REQUEST_METHOD"] == "GET") {

        if (count($_GET) && $_GET['submit'] == 'Reset') {
            $res = $main->initDB();
            $message = $res['data'];
        }
    }

    // ORDER METHOD
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        switch ($_POST['submit']) {
            case 'Add': {
                    $amountSale = test_input($_POST['amountSale']);
                    $amountClaim = test_input($_POST['amountClaim']);
                    $currencyId = test_input($_POST['currencyId']);

                    if ($amountSale == '0.00') {
                        $message = 'Missing amount of sale';
                    } else if (!$currencyId) {
                        $message = 'Choose the currency to use';
                    } else {
                        $addRes = $main->placeOrder($USER_ID, $currencyId, $amountSale, $amountClaim);
                        $message = 'Order successfully stored.';
                    }
                }
                break;

                break;
            case 'Pay': {
                    $orderId = test_input($_POST['orderId']);

                    if ($orderId) {
                        $res = $main->payOrder($orderId);
                        $message = processMsg($res);
                    }
                }
                break;
            case 'Complete': {
                    $orderId = test_input($_POST['orderId']);

                    if ($orderId) {
                        $res = $main->deliveredOrder($orderId);
                        $message = processMsg($res);
                    }
                }
                break;

            default:
                break;
        }
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function processMsg($res)
    {
        if ($res['success']) {
            return 'Success update order status';
        } else {
            return $res['data'];
        }
    }

    ?>

    <h2>Sale Order Form</h2>
    <h3>Reset DB</h3>
    <p>Click the Button below to reset DB back to original state</p>

    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="submit" name="submit" value="Reset">
        <br>
    </form>
    <p>_________________________________________________________</p>
    <br>

    <span class="error"><?php echo $message; ?></span>

    <h3>Details</h3>
    <p><b>Point Details: <?php echo $pointLeft ?> pts</b></p>
    <br>

    <h3>Add Order</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Amount Sale: <input type="text" name="amountSale" placeholder="0.00">
        Point to Use: <input type="text" name="amountClaim" placeholder="0.00" value="0.00">
        <br>

        Currency:
        <input checked type="radio" name="currencyId" value="1">USD
        <input type="radio" name="currencyId" value="2">MYR
        <br><br>

        <input type="submit" name="submit" value="Add">
    </form>
    <br>

    <h3>Update Order</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Order ID: <input type="text" name="orderId">
        <br><br>
        <input type="submit" name="submit" value="Pay">
        <input type="submit" name="submit" value="Complete">
    </form>
    <br>

    <h3>Previous Order List</h3>
    <?php
    $arrlength = count($orderList);

    if ($arrlength > 0) {
        echo implode(', ', array_keys($orderList[0]));
        echo "<br>";

        for ($x = 0; $x < $arrlength; $x++) {
            echo implode(', ', $orderList[$x]);
            echo "<br>";
        }
    } else echo '<p>No previous order</p>'

    ?>
    <br>

</body>

</html>