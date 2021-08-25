<?php

namespace IWD\PaypalPos\Api\Data;

interface CategoryLoggerSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get an array of objects
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface[]
     */
    public function getItems();

    /**
     * Set objects list
     * @param \IWD\PaypalPos\Api\Data\CategoryLoggerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
