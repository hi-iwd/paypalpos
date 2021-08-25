<?php

namespace IWD\PaypalPos\Api\Data;

interface ProductLoggerSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get an array of objects
     * @return \IWD\PaypalPos\Api\Data\ProductLoggerInterface[]
     */
    public function getItems();

    /**
     * Set objects list
     * @param \IWD\PaypalPos\Api\Data\ProductLoggerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
