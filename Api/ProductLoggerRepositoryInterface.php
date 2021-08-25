<?php

namespace IWD\PaypalPos\Api;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface ProductLoggerRepositoryInterface
{
    /**
     * Save object
     * @param \IWD\PaypalPos\Api\Data\ProductLoggerInterface $object
     * @return \IWD\PaypalPos\Api\Data\ProductLoggerInterface
     */
    public function save(\IWD\PaypalPos\Api\Data\ProductLoggerInterface $object);

    /**
     * Get object by id
     * @param int $id
     * @return \IWD\PaypalPos\Api\Data\ProductLoggerInterface
     */
    public function getById($id);

    /**
     * Get by ProductId value
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\ProductLoggerInterface
     */
    public function getByProductId($value);

    /**
     * Delete object
     * @param \IWD\PaypalPos\Api\Data\ProductLoggerInterface $object
     * @return boolean
     */
    public function delete(\IWD\PaypalPos\Api\Data\ProductLoggerInterface $object);

    /**
     * Get a list of object
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \IWD\PaypalPos\Api\Data\ProductLoggerSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
