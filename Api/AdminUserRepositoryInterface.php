<?php

namespace IWD\PaypalPos\Api;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface AdminUserRepositoryInterface
{
    /**
     * Get object by id
     * @param int $id
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface
     */
    public function getById($id);

    /**
     * Get a list of object
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \IWD\PaypalPos\Api\Data\AdminUserSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
