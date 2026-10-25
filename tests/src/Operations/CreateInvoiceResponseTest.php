<?php

namespace Invoicetic\Fgoro\Tests\Operations;

use Invoicetic\Common\Dto\Invoice\Invoice;
use Invoicetic\Fgoro\Operations\CreateInvoiceRequest;
use Invoicetic\Fgoro\Tests\AbstractTest;
use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientInterface;

class CreateInvoiceResponseTest extends AbstractTest
{
    public function test_process()
    {
        $data = json_decode(file_get_contents(TEST_FIXTURE_PATH . '/responses/create_invoice/success.json'), true);

        $request = $this->requestWithResponse(201, $data);

        $invoice = new Invoice();
        $request->setInvoice($invoice);
        $response = $request->sendData([]);
        self::assertTrue($response->isSuccessful());
        self::assertSame(201, $response->getStatusCode());
        self::assertSame("", $response->getMessage());

        $responseInvoice = $response->getInvoice();
        self::assertInstanceof(Invoice::class,$responseInvoice);
        self::assertSame("SPT", $responseInvoice->getIdSequence()->getSequence());
        self::assertSame(223, $responseInvoice->getIdSequence()->getNumber());
        self::assertSame($data['meta'], $response->getData()['meta']);
    }

    public function test_http_errors_preserve_problem_details_and_invoice()
    {
        foreach ([400, 403, 404, 429, 500] as $status) {
            $problem = [
                'type' => 'https://api.fgo.ro/problems/example',
                'title' => 'Request failed',
                'detail' => 'Invoice could not be issued.',
                'status' => 200,
                'Success' => true,
                'data' => ['serie' => 'WRONG', 'numar' => '999'],
                'errors' => ['client' => ['Invalid client']],
            ];
            $request = $this->requestWithResponse($status, $problem);
            $invoice = Invoice::denormalize(['idSequence' => ['sequence' => 'ORIGINAL', 'number' => 12]]);
            $request->setInvoice($invoice);

            $response = $request->sendData([]);

            self::assertFalse($response->isSuccessful());
            self::assertSame($status, $response->getStatusCode());
            self::assertSame($problem['detail'], $response->getMessage());
            self::assertSame($problem['type'], $response->getCode());
            self::assertSame($problem['errors'], $response->getData()['errors']);
            self::assertSame($invoice, $response->getInvoice());
            self::assertSame('ORIGINAL', $invoice->getIdSequence()->getSequence());
            self::assertSame(12, $invoice->getIdSequence()->getNumber());
        }
    }

    public function test_problem_title_is_used_when_detail_is_absent()
    {
        $request = $this->requestWithResponse(403, ['title' => 'Forbidden']);
        $response = $request->sendData([]);

        self::assertSame('Forbidden', $response->getMessage());
        self::assertNull($response->getCode());
    }

    public function test_success_uses_http_status_not_v1_flag()
    {
        $request = $this->requestWithResponse(200, [
            'Success' => false,
            'data' => ['serie' => 'SPT', 'numar' => '223'],
        ]);
        $request->setInvoice(new Invoice());

        self::assertTrue($request->sendData([])->isSuccessful());
    }

    public function test_success_requires_v2_envelope()
    {
        $request = $this->requestWithResponse(200, ['Success' => true]);
        $this->expectException(\UnexpectedValueException::class);
        $request->sendData([]);
    }

    public function test_success_requires_invoice_identity_without_modifying_invoice()
    {
        $request = $this->requestWithResponse(201, ['data' => []]);
        $invoice = Invoice::denormalize(['idSequence' => ['sequence' => 'ORIGINAL', 'number' => 12]]);
        $request->setInvoice($invoice);

        try {
            $request->sendData([]);
            self::fail('Expected invalid invoice response to throw.');
        } catch (\UnexpectedValueException $exception) {
            self::assertSame('ORIGINAL', $invoice->getIdSequence()->getSequence());
            self::assertSame(12, $invoice->getIdSequence()->getNumber());
        }
    }

    public function test_invalid_json_throws()
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())->method('sendRequest')->willReturn(new Response(502, [], '<html>Bad gateway</html>'));
        $request = new CreateInvoiceRequest($client, null);
        $request->initialize([
            'login_id' => '123456789',
            'secret_key' => 'v2-api-key',
            'endpoint' => 'https://api.fgo.ro/v2',
        ]);

        $this->expectException(\JsonException::class);
        $request->sendData([]);
    }

    private function requestWithResponse(int $status, array $data): CreateInvoiceRequest
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())->method('sendRequest')->willReturn(
            new Response($status, ['Content-Type' => 'application/problem+json'], json_encode($data, JSON_THROW_ON_ERROR))
        );
        $request = new CreateInvoiceRequest($client, null);
        $request->initialize([
            'login_id' => '123456789',
            'secret_key' => 'v2-api-key',
            'endpoint' => 'https://api.fgo.ro/v2',
        ]);

        return $request;
    }
}
