<?php

namespace IWD\PaypalPos\Api;

interface GuestDefaultShippingInformationManagementInterface
{
    /**
     * @param string $cartId
     * @return \Magento\Checkout\Api\Data\PaymentDetailsInterface
     */
    public function saveDefaultAddressInformation(
        $cartId
    );
}
