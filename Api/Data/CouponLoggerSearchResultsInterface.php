<?php

namespace IWD\PaypalPos\Api\Data;

interface CouponLoggerSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get an array of objects
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerInterface[]
     */
    public function getItems();

    /**
     * Set objects list
     * @param \IWD\PaypalPos\Api\Data\CouponLoggerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
