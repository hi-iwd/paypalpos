<?php

namespace IWD\PaypalPos\Api;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface UserAttributeRepositoryInterface
{
    /**
     * Save object
     * @param \IWD\PaypalPos\Api\Data\UserAttributeInterface $object
     * @return \IWD\PaypalPos\Api\Data\UserAttributeInterface
     */
    public function save(\IWD\PaypalPos\Api\Data\UserAttributeInterface $object);

    /**
     * Get object by id
     * @param int $id
     * @return \IWD\PaypalPos\Api\Data\UserAttributeInterface
     */
    public function getById($id);

    /**
     * Get by OrderId value
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\UserAttributeInterface
     */
    public function getByOrderId($value);

    /**
     * Delete object
     * @param \IWD\PaypalPos\Api\Data\UserAttributeInterface $object
     * @return boolean
     */
    public function delete(\IWD\PaypalPos\Api\Data\UserAttributeInterface $object);

    /**
     * Get a list of object
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \IWD\PaypalPos\Api\Data\UserAttributeSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
