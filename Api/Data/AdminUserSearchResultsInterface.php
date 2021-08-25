<?php

namespace IWD\PaypalPos\Api\Data;

interface AdminUserSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get an array of objects
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface[]
     */
    public function getItems();

    /**
     * Set objects list
     * @param \IWD\PaypalPos\Api\Data\AdminUserInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
