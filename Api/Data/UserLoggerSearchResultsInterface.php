<?php

namespace IWD\PaypalPos\Api\Data;

interface UserLoggerSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get an array of objects
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface[]
     */
    public function getItems();

    /**
     * Set objects list
     * @param \IWD\PaypalPos\Api\Data\UserLoggerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
