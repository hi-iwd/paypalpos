<?php

namespace IWD\PaypalPos\Api;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface CouponLoggerRepositoryInterface
{
    /**
     * Save object
     * @param \IWD\PaypalPos\Api\Data\CouponLoggerInterface $object
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerInterface
     */
    public function save(\IWD\PaypalPos\Api\Data\CouponLoggerInterface $object);

    /**
     * Get object by id
     * @param int $id
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerInterface
     */
    public function getById($id);

    /**
     * Get by RuleId value
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerInterface
     */
    public function getByRuleId($value);

    /**
     * Get by IsUsed value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerInterface
     */
    public function getByIsUsed($value);

    /**
     * Delete object
     * @param \IWD\PaypalPos\Api\Data\CouponLoggerInterface $object
     * @return boolean
     */
    public function delete(\IWD\PaypalPos\Api\Data\CouponLoggerInterface $object);

    /**
     * Get a list of object
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
