<?php

namespace IWD\PaypalPos\Api;

/**
 * Rule management interface
 */
interface GuestCartPriceRuleManagementInterface
{
    /**
     * @param string $cartId
     * @param int $amountDiscount
     * @return mixed
     */
    public function createPercent($cartId, $amountDiscount);

    /**
     * @param string $cartId
     * @param int $amountDiscount
     * @return mixed
     */
    public function createFixed($cartId, $amountDiscount);
}
