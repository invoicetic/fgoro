<?php

namespace Invoicetic\Fgoro\Tests\Operations;

use Invoicetic\Common\Dto\Invoice\Invoice;
use Invoicetic\Fgoro\Operations\CreateInvoiceResponse;
use Invoicetic\Fgoro\Tests\AbstractTest;

class CreateInvoiceResponseTest extends AbstractTest
{
    public function test_process()
    {
        $data = json_decode(file_get_contents(TEST_FIXTURE_PATH . '/responses/create_invoice/success.json'), true);

        $request = new \Invoicetic\Fgoro\Operations\CreateInvoiceRequest(null, null);

        $invoice = new Invoice();
        $request->setInvoice($invoice);
        $data['invoice'] = $invoice;

        $response = new CreateInvoiceResponse($request, $data);
        self::assertTrue($response->isSuccessful());
        self::assertSame("", $response->getMessage());

        $responseInvoice = $response->getInvoice();
        self::assertInstanceof(Invoice::class,$responseInvoice);
        self::assertSame("SPT", $responseInvoice->getIdSequence()->getSequence());
        self::assertSame(223, $responseInvoice->getIdSequence()->getNumber());
    }
}
