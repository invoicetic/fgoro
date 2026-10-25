<?php

namespace Invoicetic\Fgoro\Operations;

use Invoicetic\Common\Dto\Invoice\Invoice;
use Invoicetic\Common\Gateway\Operations\CreateInvoiceResponseTrait;
use Invoicetic\Common\InvoiceId\Dto\InvoiceIdSequence;
use UnexpectedValueException;

class CreateInvoiceResponse extends AbstractResponse
{
    use CreateInvoiceResponseTrait;

    protected function parseInvoiceFromData($data)
    {
        if (!$this->isSuccessful()) {
            return;
        }

        $invoice = $this->getInvoice();
        if (false == ($invoice instanceof Invoice)) {
            return;
        }

        $dataFactura = $data['data'] ?? null;
        if (!is_array($dataFactura)
            || !isset($dataFactura['serie'], $dataFactura['numar'])
            || !is_string($dataFactura['serie'])
            || (!is_string($dataFactura['numar']) && !is_int($dataFactura['numar']))
        ) {
            throw new UnexpectedValueException('Missing or invalid invoice identity in FGO API v2 response.');
        }

        $idSequence = $invoice->getIdSequence();
        if (false == ($idSequence instanceof InvoiceIdSequence)) {
            $idSequence = new InvoiceIdSequence();
            $invoice->setIdSequence($idSequence);
        }

        $idSequence->setSequence($dataFactura['serie']);
        $idSequence->setNumber($dataFactura['numar']);
    }
}
