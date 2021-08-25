<?php

namespace IWD\PaypalPos\Api;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface SalesCouponLoggerRepositoryInterface
{
    /**
     * Save object
     * @param \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface $object
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface
     */
    public function save(\IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface $object);

    /**
     * Get object by id
     * @param int $id
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface
     */
    public function getById($id);

    /**
     * Get by CouponId value
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface
     */
    public function getByCouponId($value);

    /**
     * Delete object
     * @param \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface $object
     * @return boolean
     */
    public function delete(\IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface $object);

    /**
     * Get a list of object
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
