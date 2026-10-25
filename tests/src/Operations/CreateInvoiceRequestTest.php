<?php

namespace Invoicetic\Fgoro\Tests\Operations;

use Invoicetic\Common\Dto\Invoice\Invoice;
use Invoicetic\Common\Exception\InvalidRequestException;
use Invoicetic\Fgoro\FgoroGateway;
use Invoicetic\Fgoro\Operations\CreateInvoiceRequest;
use Invoicetic\Fgoro\Tests\AbstractTest;
use Nyholm\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class CreateInvoiceRequestTest extends AbstractTest
{
    public function test_gateway_sends_authenticated_v2_json_request()
    {
        $invoice = $this->invoice();
        $client = $this->createMock(ClientInterface::class);
        $client->expects(self::once())->method('sendRequest')->willReturnCallback(
            function (RequestInterface $request) {
                self::assertSame('POST', $request->getMethod());
                self::assertSame('https://api.fgo.ro/v2/facturi', (string) $request->getUri());
                self::assertSame('Bearer v2-api-key', $request->getHeaderLine('Authorization'));
                self::assertSame('123456789', $request->getHeaderLine('Fgo-Cod-Unic'));
                self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
                self::assertSame('application/json, application/problem+json', $request->getHeaderLine('Accept'));

                $data = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
                self::assertSame('Factura', $data['tip_factura']);
                self::assertSame('TST', $data['serie']);
                self::assertSame('RON', $data['valuta']);
                self::assertSame('order_4f1c9b2e', $data['id_extern']);
                self::assertSame('PJ', $data['client']['tip']);
                self::assertSame('Test & Co', $data['client']['denumire']);
                self::assertSame(123456789, $data['client']['cod_unic']);
                self::assertSame('RO', $data['client']['tara']);
                self::assertSame('2', $data['continut'][0]['nr_produse']);
                self::assertSame(21, $data['continut'][0]['cota_tva']);
                self::assertSame(10, $data['continut'][0]['pret_unitar']);
                self::assertSame(0, $data['continut'][1]['cota_tva']);
                self::assertSame(50, $data['continut'][1]['pret_total']);
                self::assertArrayNotHasKey('pret_unitar', $data['continut'][1]);
                $this->assertSnakeCaseKeys($data);
                self::assertArrayNotHasKey('hash', $data);
                self::assertArrayNotHasKey('cod_unic', $data);
                self::assertArrayNotHasKey('platforma_url', $data);
                self::assertStringNotContainsString('v2-api-key', (string) $request->getBody());

                return new Response(201, ['Content-Type' => 'application/json'], '{"data":{"serie":"TST","numar":"7"},"meta":{}}');
            }
        );
        $gateway = new FgoroGateway($client);
        $gateway->initialize(['login_id' => '123456789', 'secret_key' => 'v2-api-key']);

        $response = $gateway->createInvoice($invoice)->send();

        self::assertTrue($response->isSuccessful());
        self::assertSame($invoice, $response->getInvoice());
        self::assertSame(7, $invoice->getIdSequence()->getNumber());
    }

    public function test_physical_person_uses_pf()
    {
        $invoice = $this->invoice();
        $invoice->getAccountingCustomerParty()->setLegalEntity(null);
        $request = new CreateInvoiceRequest(null, null);
        $request->initialize(['login_id' => '123456789', 'secret_key' => 'v2-api-key', 'invoice' => $invoice]);

        self::assertSame('PF', $request->getData()['client']['tip']);
    }

    public function test_credentials_are_required()
    {
        foreach (['login_id', 'secret_key'] as $missing) {
            $parameters = ['login_id' => '123456789', 'secret_key' => 'v2-api-key', 'invoice' => $this->invoice()];
            unset($parameters[$missing]);
            $request = new CreateInvoiceRequest(null, null);
            $request->initialize($parameters);
            try {
                $request->getData();
                self::fail('Expected missing credentials to throw.');
            } catch (InvalidRequestException $exception) {
                self::assertStringContainsString($missing, $exception->getMessage());
            }
        }
    }

    public function test_v1_sandbox_is_rejected()
    {
        $gateway = new FgoroGateway();
        $this->expectException(\LogicException::class);
        $gateway->initialize(['sandbox' => true]);
    }

    public function test_explicit_v2_endpoint_is_forwarded()
    {
        $gateway = new FgoroGateway();
        $gateway->initialize([
            'endpoint' => 'https://example.test/v2',
            'login_id' => '123456789',
            'secret_key' => 'v2-api-key',
        ]);

        self::assertSame(
            'https://example.test/v2/facturi',
            $gateway->createInvoice($this->invoice())->getEndpointUrl()
        );
    }

    private function assertSnakeCaseKeys(array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_string($key)) {
                self::assertMatchesRegularExpression('/^[a-z]+(?:_[a-z]+)*$/', $key);
            }
            if (is_array($value)) {
                $this->assertSnakeCaseKeys($value);
            }
        }
    }

    private function invoice(): Invoice
    {
        $invoice = Invoice::denormalize([
            'documentCurrencyCode' => 'RON',
            'idSequence' => ['sequence' => 'TST'],
            'orderReference' => ['id' => 'order_4f1c9b2e'],
            'accountingCustomerParty' => [
                'name' => 'Test &amp; Co',
                'partyIdentification' => 'RO123456789',
                'legalEntity' => ['registrationName' => 'Test & Co'],
                'postalAddress' => [
                    'streetName' => 'Strada Test',
                    'buildingNumber' => '1',
                    'cityName' => 'Bucuresti',
                    'countrySubentity' => 'B',
                    'country' => ['identificationCode' => 'RO'],
                ],
                'contact' => ['email' => 'test@example.test', 'telephone' => '123456789'],
            ],
            'invoiceLines' => [
                [
                    'item' => ['name' => 'Product', 'description' => 'Details', 'classifiedTaxCategory' => ['percent' => 21]],
                    'price' => ['priceAmount' => 10],
                    'invoicedQuantity' => ['quantity' => 2],
                ],
                [
                    'item' => ['name' => 'Service'],
                    'price' => ['priceAmount' => 25],
                    'invoicedQuantity' => ['quantity' => 2],
                ],
            ],
        ]);
        $invoice->getInvoiceLines()[1]->setTotalAmount(50);

        return $invoice;
    }
}
