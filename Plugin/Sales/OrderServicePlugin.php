<?php

namespace IWD\PaypalPos\Plugin\Sales;

use Magento\Sales\Api\InvoiceOrderInterface;
use Magento\Sales\Api\Data\OrderInterface;
use IWD\PaypalPos\Model\Payment\VenmoQR;

class OrderServicePlugin
{
    const PAYMENT_CODE = 'paypalpos';
    /**
     * @var InvoiceOrderInterface
     */
    private $invoiceOrder;

    /**
     * OrderServicePlugin constructor.
     * @param InvoiceOrderInterface $invoiceOrder
     */
    public function __construct(InvoiceOrderInterface $invoiceOrder)
    {
        $this->invoiceOrder = $invoiceOrder;
    }

    /**
     * @param $subject
     * @param $order
     * @return mixed
     */
    public function afterPlace($subject, OrderInterface $order)
    {
        $paymentMethod = $order->getPayment()->getMethod();
        if ((self::PAYMENT_CODE == $paymentMethod || VenmoQR::PAYMENT_METHOD_CODE == $paymentMethod) && $order->canInvoice()) {
            $this->invoiceOrder->execute($order->getId());
        }
        return $order;
    }
}
