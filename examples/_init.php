<?php

use Invoicetic\Invoicetic;

require dirname(__DIR__).DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'bootstrap.php';

/** @var \Invoicetic\Fgoro\FgoroGateway $gateway */
$gateway = Invoicetic::create('fgoro');;

$gateway->initialize(
    [
        'login_id' => $_ENV['FGO_LOGIN_ID'],
        'secret_key' => $_ENV['FGO_SECRET_KEY'],
        'sandbox' => true,
    ]
);

return $gateway;
