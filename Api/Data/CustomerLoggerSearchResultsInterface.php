<?php

namespace IWD\PaypalPos\Api\Data;

interface CustomerLoggerSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get an array of objects
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerInterface[]
     */
    public function getItems();

    /**
     * Set objects list
     * @param \IWD\PaypalPos\Api\Data\CustomerLoggerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
