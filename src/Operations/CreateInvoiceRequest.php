<?php

namespace Invoicetic\Fgoro\Operations;

use Invoicetic\Common\Dto\InvoiceLine\InvoiceLine;
use Invoicetic\Common\Gateway\Operations\CreateInvoiceRequestTrait;
use Invoicetic\Common\UnitCode\UnitCodeName;
use Invoicetic\Fgoro\Support\Client\ClientFactory;
use Invoicetic\Fgoro\Support\Invoice\InvoiceFactory;

class CreateInvoiceRequest extends AbstractRequest
{
    use CreateInvoiceRequestTrait;

    const ENDPOINT = '/facturi';

    public function getData(): array
    {
        $this->validateParameters('login_id', 'secret_key', 'invoice');

        $data = $this->generateInvoiceData();
        $data['client'] = $this->generateClientData();
        $data['continut'] = $this->generateInvoiceLines();

        return $data;
    }

    protected function generateInvoiceData(): array
    {
        $invoice = $this->getInvoice();
        $invoice = InvoiceFactory::fromInvoice($invoice);
        return $invoice->toArray();
    }

    protected function generateClientData(): array
    {
        /* @var $invoice \Invoicetic\Common\Dto\Invoice\Invoice */
        $invoice = $this->getInvoice();
        $customer = $invoice->getAccountingCustomerParty();
        $client = ClientFactory::fromParty($customer);
        return $client->toArray();
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    protected function generateInvoiceLines()
    {
        $data = [];
        $invoice = $this->getInvoice();
        $lines = $invoice->getInvoiceLines();
        foreach ($lines as $line) {
            $data[] = $this->generateInvoiceLine($line);
        }
        return $data;
    }

    /**
     * @param InvoiceLine $line
     * @return array
     */
    protected function generateInvoiceLine(InvoiceLine $line): array
    {
        $item = $line->getItem();
        $price = $line->getPrice();
        $quantity = $line->getInvoicedQuantity();
        $taxCategory = $item->getClassifiedTaxCategory();
        $data = [
            'denumire' => $item->getName(),
            'descriere' => $item->getDescription(),
            'um' => UnitCodeName::for($quantity->getUnitCode(), 'ro'),
            'nr_produse' => $quantity->getQuantity(),
            'cota_tva' => $taxCategory?->getPercent() ?? 0,
        ];
        if ($line->hasTotalAmount()) {
            $data['pret_total'] = $line->getTotalAmount();
        } else {
            $data['pret_unitar'] = $price->getPriceAmount();
        }
        return $data;
    }
}
