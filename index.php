<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';

use App\Model\Transactions_model;

	$transaction = new Transactions_model();
    echo "<pre>";
	var_dump($transaction->fetch());


