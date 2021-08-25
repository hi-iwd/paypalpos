<?php

namespace IWD\PaypalPos\Api\Data;

interface UserAttributeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get an array of objects
     * @return \IWD\PaypalPos\Api\Data\UserAttributeInterface[]
     */
    public function getItems();

    /**
     * Set objects list
     * @param \IWD\PaypalPos\Api\Data\UserAttributeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
