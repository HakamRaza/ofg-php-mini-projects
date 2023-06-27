<?php

declare(strict_types=1);

namespace App\Helper;

use App\Interfaces\DBInterface;
use PDO;
use PDOException;

/**
 * @mixin PDO
 */
class DB implements DBInterface
{
    private PDO $pdo;

    /**
     * Create a new DB instance
     *
     * @return void
     */
    public function __construct()
    {
        $SETTINGS = parse_ini_file(__DIR__ . '/../../.env', true);
        $DB_NAME  = $SETTINGS['db']['name'];
        $DB_HOST  = $SETTINGS['db']['host'];
        $DB_PORT  = $SETTINGS['db']['port'];

        $defaultOptions = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        // establishing database connection using PDO
        try {
            $this->pdo = new PDO(
                "mysql:hostname={$DB_HOST};dbname={$DB_NAME};port={$DB_PORT}",
                $SETTINGS['db']['user'],
                $SETTINGS['db']['pass'],
                $DB_OPTION ?? $defaultOptions
            );
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Execute query with given arguments
     * 
     * @param string $name
     * @param array $arguments
     */
    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->pdo, $name], $arguments);
    }
}
