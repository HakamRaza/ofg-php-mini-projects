<?php

namespace App\Interfaces;

interface DBInterface
{
    /**
     * Create a new DB instance
     *
     * @return void
     */
    public function __construct();

    /**
     * Execute query with given arguments
     * 
     * @param string $name
     * @param array $arguments
     */
    public function __call(string $name, array $arguments);
}
