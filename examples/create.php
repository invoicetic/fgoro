<?php

use Invoicetic\Common\Dto\Invoice\Invoice;

$gateway = require '_init.php';

$parameters = [
    'DocumentCurrencyCode' => 'RON',
    'idSequence' => [
        'sequence' => 'INV',
    ],
    'accountingCustomerParty' => [
        'name' => 'SC Test SRL',
        'partyIdentification' => '123456789',
        'postalAddress' => [
            'streetName' => 'Strada 1',
            'buildingNumber' => '1',
            'cityName' => 'Bucuresti',
            'postalZone' => '123456',
            'country' => 'RO',
        ],
        'legalEntity' => [
            'registrationName' => 'SC Test SRL',
            'companyId' => '123456789',
        ],
    ],
    'invoiceLines' => [
        [
            'id' => '1',
            'item' => [
                'name' => 'Test name 1',
                'description' => 'Test description 1',
                'classifiedTaxCategory' => [
                    'id' => '1',
                    'percent' => 19,
                    'taxScheme' => 'VAT',
                ],
            ],
            'price' => [
                'priceAmount' => 10,
                'baseQuantity' => 1,
            ],
            'invoicedQuantity' => ['quantity' => 1],
        ],
        [
            'id' => '2',
            'item' => [
                'name' => 'Test name 2',
                'description' => 'Test description 2',
            ],
            'price' => [
                'priceAmount' => 20,
                'baseQuantity' => 2,
            ],
            'invoicedQuantity' => ['quantity' => 2],
        ],
    ]
];

$invoice = Invoice::denormalize($parameters);

$request = $gateway->createInvoice($invoice);
$response = $request->send();

// Send the Symfony HttpRedirectResponse
var_dump($response);