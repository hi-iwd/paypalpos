<?php

namespace IWD\PaypalPos\Api;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface CustomerLoggerRepositoryInterface
{
    /**
     * Save object
     * @param \IWD\PaypalPos\Api\Data\CustomerLoggerInterface $object
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerInterface
     */
    public function save(\IWD\PaypalPos\Api\Data\CustomerLoggerInterface $object);

    /**
     * Get object by id
     * @param int $id
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerInterface
     */
    public function getById($id);

    /**
     * Get by Email value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerInterface
     */
    public function getByEmail($value);

    /**
     * Delete object
     * @param \IWD\PaypalPos\Api\Data\CustomerLoggerInterface $object
     * @return boolean
     */
    public function delete(\IWD\PaypalPos\Api\Data\CustomerLoggerInterface $object);

    /**
     * Get a list of object
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
