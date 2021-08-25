<?php

namespace IWD\PaypalPos\Api;

/**
 * Rule management interface
 */
interface CartPriceRuleManagementInterface
{
    /**
     * @param int $cartId
     * @param int $amountDiscount
     * @return mixed
     */
    public function createPercent($cartId, $amountDiscount);

    /**
     * @param int $cartId
     * @param int $amountDiscount
     * @return mixed
     */
    public function createFixed($cartId, $amountDiscount);
}
