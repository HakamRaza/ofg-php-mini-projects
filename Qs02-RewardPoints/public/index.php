
<?php

use App\Migration\DBInit;

require_once __DIR__ . '/../vendor/autoload.php';

class Index
{
    public function init()
    {
        $init = new DBInit();
        $init->migrateTable();
    }
}


?>