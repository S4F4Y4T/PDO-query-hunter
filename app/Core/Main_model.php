<?php

namespace App\Core;

use App\Config\Config;

class Main_model
{
    protected Database $db;

    public function __construct()
    {
        $dsn = "mysql:dbname=" . Config::DB_NAME . "; host=" . Config::DB_HOST;
        $this->db = new Database($dsn, Config::DB_USERNAME, Config::DB_PASSWORD);
    }
}