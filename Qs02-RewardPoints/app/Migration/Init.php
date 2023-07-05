<?php

declare(strict_types=1);

namespace App\Migration;

use App\Helper\DB;
use Exception;

class Init
{
    protected $db;

    /**
     * Create DB instance
     */
    public function __construct()
    {
        $this->db = new DB();
    }

    /**
     * Execute migration
     */
    public function migrateTable()
    {
        $queries = [
            'DROP TABLE IF EXISTS `point_rewards`;',
            'DROP TABLE IF EXISTS `sales_order`;',
            'DROP TABLE IF EXISTS `users`;',
            'DROP TABLE IF EXISTS `transaction_type`;',
            'DROP TABLE IF EXISTS `currency`;',
            'DROP TABLE IF EXISTS `order_status`;',

            'CREATE TABLE IF NOT EXISTS `transaction_type` (
            `id` TINYINT UNSIGNED NOT NULL,
            `type` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
            PRIMARY KEY (`id`)
          );',

            'CREATE TABLE IF NOT EXISTS `currency` (
            `id` INT UNSIGNED NOT NULL,
            `currency_code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
            `country_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
            `conversion_rate_usd` decimal(15, 10) NOT NULL,
            PRIMARY KEY (`id`)
          );',

            'CREATE TABLE IF NOT EXISTS `order_status` (
            `id` TINYINT UNSIGNED NOT NULL,
            `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
            PRIMARY KEY (`id`)
          );',

            'CREATE TABLE IF NOT EXISTS `users` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
            `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            PRIMARY KEY (`id`)
          );',

            'CREATE TABLE IF NOT EXISTS `point_rewards` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `user_id` BIGINT UNSIGNED NOT NULL,
            `sales_order_id` BIGINT UNSIGNED NOT NULL,
            `transaction_type_id` TINYINT UNSIGNED NOT NULL,
            `points` INT UNSIGNED NOT NULL,
            `expired_at` TIMESTAMP NULL DEFAULT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NOW(),
            PRIMARY KEY (`id`)
          );',

            'CREATE TABLE IF NOT EXISTS `sales_order` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `user_id` BIGINT UNSIGNED NOT NULL,
            `total_sales` INT UNSIGNED NOT NULL,
            `currency_id` INT UNSIGNED NOT NULL,
            `order_status_id` TINYINT UNSIGNED NOT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NOW(),
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`) );',


            'ALTER TABLE `point_rewards` ADD CONSTRAINT `point_rewards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;',
            'ALTER TABLE `point_rewards` ADD CONSTRAINT `point_rewards_transaction_type_id_foreign` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_type` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;',
            'ALTER TABLE `point_rewards` ADD CONSTRAINT `point_rewards_sales_order_id_foreign` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_order` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;',

            'ALTER TABLE `sales_order` ADD CONSTRAINT `sales_order_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;',
            'ALTER TABLE `sales_order` ADD CONSTRAINT `sales_order_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;',
            'ALTER TABLE `sales_order` ADD CONSTRAINT `sales_order_order_status_id_foreign` FOREIGN KEY (`order_status_id`) REFERENCES `order_status` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;',

            'INSERT INTO `users` (`username`, `password`) VALUES 
            ("admin", "password123");',

            'INSERT INTO `transaction_type` (`id`, `type`) VALUES 
            (1, "Debit"),
            (2, "Credit");',

            'INSERT INTO `order_status` (`id`, `status`) VALUES 
            (1, "Pending"),
            (2, "In Progress"),
            (3, "Complete"),
            (4, "Cancel");',

            'INSERT INTO `currency` (`id`, `currency_code`, `country_name`, `conversion_rate_usd`) VALUES 
            (1, "USD", "United States", 1.000000), 
            (2, "MYR", "Malaysia", 4.000000);',
        ];

        foreach ($queries as $query) {
            try {
                $statement = $this->db->prepare($query);
                $statement->execute();
            } catch (\Throwable $th) {
                throw new Exception('Migrations failed. ' . $th->getMessage());
            }
        }
    }
}
