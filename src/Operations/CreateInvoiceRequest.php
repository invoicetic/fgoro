<?php

namespace Invoicetic\Fgoro\Operations;

use Invoicetic\Common\Dto\InvoiceLine\InvoiceLine;
use Invoicetic\Common\Gateway\Operations\CreateInvoiceRequestTrait;

class CreateInvoiceRequest extends AbstractRequest
{
    use CreateInvoiceRequestTrait;

    const ENDPOINT = '/factura/emitere';

    public function getData(): array
    {
        $this->validateParameters('login_id', 'secret_key', 'invoice', 'platforma_url');

        $data = $this->generateBaseData();

        $data = array_merge($data, $this->generateInvoiceData());
        $data['Client'] = $this->generateClientData();
        $data['Continut'] = $this->generateInvoiceLines();
        $data['Hash'] = $this->generateHash($data);

        return $data;
    }

    protected function generateInvoiceData(): array
    {
        $data = [];
        $invoice = $this->getInvoice();
        $data['TipFactura'] = 'Factura';
        $data['Serie'] = $invoice->getIdSequence()->getSequence();
        $data['Valuta'] = $invoice->getDocumentCurrencyCode();
        return $data;
    }

    protected function generateClientData()
    {
        $data = [];
        $invoice = $this->getInvoice();
        $customer = $invoice->getAccountingCustomerParty();
        $data['Denumire'] = $customer->getName();

        $identifier = $customer->getPartyIdentification();
        $data['CodUnic'] = $identifier->getValue();

        $data['Tip'] = $customer->hasLegalEntity() ? 'PJ' : 'PF';

        $data['Judet'] = $customer->getPostalAddress()->getCountrySubentity();
        return $data;
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

    protected function generateHash($data)
    {
        return strtoupper(
            sha1(
                $data['CodUnic'] . $this->getSecretKey() . $data['Client']['Denumire']
            )
        );
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
            'Denumire' => $item->getName(),
            'Descriere' => $item->getDescription(),
            'PretUnitar' => $price->getPriceAmount(),
            'UM' => $quantity->getUnitCode(),
            'NrProduse' => $quantity->getQuantity(),
            'CotaTVA' => $taxCategory ? $taxCategory->getPercent() : 0,
        ];
        return $data;
    }
}

