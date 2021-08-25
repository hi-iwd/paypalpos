<?php

namespace IWD\PaypalPos\Model\Service;

use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Model\Order\Pdf\Invoice;

class InvoicePdfGeneratorService
{
    /**
     * @var Invoice
     */
    private $invoicePdf;

    /**
     * @param Invoice $invoicePdf
     */
    public function __construct(Invoice $invoicePdf)
    {
        $this->invoicePdf = $invoicePdf;
    }

    /**
     * @param InvoiceInterface $invoice
     * @return \Zend_Pdf
     */
    public function execute(InvoiceInterface $invoice)
    {
        return $this->invoicePdf->getPdf([$invoice]);
    }
}
