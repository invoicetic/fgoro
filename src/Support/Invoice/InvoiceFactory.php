<?php

namespace Invoicetic\Fgoro\Support\Invoice;

use Invoicetic\Common\Dto\Invoice\Invoice as InvoiceCommon;

class InvoiceFactory
{
    public static function fromInvoice(InvoiceCommon $invoiceCommon): Invoice
    {
        $invoice = new Invoice();

        self::parseIdSequence($invoiceCommon, $invoice);
        self::parseIssueDate($invoiceCommon, $invoice);
        self::parseDueDate($invoiceCommon, $invoice);
        self::parsePaymentMeans($invoiceCommon, $invoice);
        self::parseReferences($invoiceCommon, $invoice);
        return $invoice;
    }

    private static function parseIdSequence(InvoiceCommon $invoiceCommon, Invoice $invoice): void
    {
        $idSequence = $invoiceCommon->getIdSequence();
        $invoice->setSerie($idSequence->getSequence());
    }

    private static function parseIssueDate(InvoiceCommon $invoiceCommon, Invoice $invoice)
    {
    }

    private static function parseDueDate(InvoiceCommon $invoiceCommon, Invoice $invoice)
    {
    }

    private static function parsePaymentMeans(InvoiceCommon $invoiceCommon, Invoice $invoice)
    {
        $invoice->setValuta($invoiceCommon->getDocumentCurrencyCode());
    }

    private static function parseReferences(InvoiceCommon $invoiceCommon, Invoice $invoice)
    {
        $orderReference = $invoiceCommon->getOrderReference();
        $invoice->setIdExtern($orderReference?->getId());
    }
}
