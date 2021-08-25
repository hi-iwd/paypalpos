<?php

namespace IWD\PaypalPos\Api;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface CategoryLoggerRepositoryInterface
{
    /**
     * Save object
     * @param \IWD\PaypalPos\Api\Data\CategoryLoggerInterface $object
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface
     */
    public function save(\IWD\PaypalPos\Api\Data\CategoryLoggerInterface $object);

    /**
     * Get object by id
     * @param int $id
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface
     */
    public function getById($id);

    /**
     * Get by StoreId value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface
     */
    public function getByStoreId($value);

    /**
     * Get by CategoryId value
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface
     */
    public function getByCategoryId($value);

    /**
     * Get by CreatedAt value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface
     */
    public function getByCreatedAt($value);

    /**
     * Delete object
     * @param \IWD\PaypalPos\Api\Data\CategoryLoggerInterface $object
     * @return boolean
     */
    public function delete(\IWD\PaypalPos\Api\Data\CategoryLoggerInterface $object);

    /**
     * Get a list of object
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
