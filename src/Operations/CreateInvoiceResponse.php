<?php

namespace Invoicetic\Fgoro\Operations;

use Invoicetic\Common\Dto\Invoice\Invoice;
use Invoicetic\Common\Gateway\Operations\CreateInvoiceResponseTrait;
use Invoicetic\Common\InvoiceId\Dto\InvoiceIdSequence;

class CreateInvoiceResponse extends AbstractResponse
{
    use CreateInvoiceResponseTrait;

    public function isSuccessful(): bool
    {
        return isset($this->data['Success']) && $this->data['Success'] == true;
    }

    public function getMessage(): string
    {
        return $this->data['Message'] ?? '';
    }
    protected function parseInvoiceFromData($data)
    {
        $invoice = $this->getInvoice();
        if (false == ($invoice instanceof Invoice)) {
            return;
        }
        $idSequence = $invoice->getIdSequence();
        if (false == ($idSequence instanceof InvoiceIdSequence)) {
            $idSequence = new InvoiceIdSequence();
            $invoice->setIdSequence($idSequence);
        }

        $dataFactura = $data['Factura'] ?? [];

        $idSequence->setSequence($dataFactura['Serie'] ?? '');
        $idSequence->setNumber($dataFactura['Numar'] ?? '');
//        $invoice->setInvoiceDate($data['Link'] ?? null);
//        $invoice->setInvoiceDate($data['LinkPlata'] ?? null);
    }
}
