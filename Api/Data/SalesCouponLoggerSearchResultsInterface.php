<?php

namespace IWD\PaypalPos\Api\Data;

interface SalesCouponLoggerSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get an array of objects
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface[]
     */
    public function getItems();

    /**
     * Set objects list
     * @param \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
