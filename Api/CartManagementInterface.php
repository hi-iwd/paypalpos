<?php

namespace IWD\PaypalPos\Api;

use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Interface CartManagementInterface
 * @api
 */
interface CartManagementInterface
{
    /**
     * Creates an empty cart and quote for a specified customer and store.
     *
     * @param int $customerId The customer ID.
     * @return int new cart ID.
     * @throws CouldNotSaveException The empty cart and quote could not be created.
     */
    public function createNewCartForCustomer($customerId);

    /**
     * @param int $cartId
     * @return mixed
     */
    public function reserveOrderId($cartId);
}
