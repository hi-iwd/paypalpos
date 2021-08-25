<?php

namespace IWD\PaypalPos\Api;

interface DefaultShippingInformationManagementInterface
{
    /**
     * @param int $cartId
     * @return \Magento\Checkout\Api\Data\PaymentDetailsInterface
     */
    public function saveDefaultAddressInformation(
        $cartId
    );
}
