<?php

namespace IWD\PaypalPos\Model;

use IWD\PaypalPos\Api\GuestDefaultShippingInformationManagementInterface;
use IWD\PaypalPos\Api\DefaultShippingInformationManagementInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;

class GuestDefaultShippingInformationManagement implements GuestDefaultShippingInformationManagementInterface
{
    /**
     * @var DefaultShippingInformationManagementInterface
     */
    private $defaultShippingInformationManagement;
    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    public function __construct(
        DefaultShippingInformationManagementInterface $defaultShippingInformationManagement,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->defaultShippingInformationManagement = $defaultShippingInformationManagement;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * @inheritDoc
     */
    public function saveDefaultAddressInformation($cartId)
    {
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->defaultShippingInformationManagement->saveDefaultAddressInformation($quoteIdMask->getQuoteId());
    }
}
