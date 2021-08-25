<?php

namespace IWD\PaypalPos\Api;

/**
 * Interface GuestCartManagementInterface
 * @api
 */
interface GuestCartManagementInterface
{
    /**
     * @param string $cartId
     * @return mixed
     */
    public function reserveOrderId($cartId);
}
