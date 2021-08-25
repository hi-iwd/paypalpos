<?php

namespace IWD\PaypalPos\Api;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface UserLoggerRepositoryInterface
{
    /**
     * Save object
     * @param \IWD\PaypalPos\Api\Data\UserLoggerInterface $object
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface
     */
    public function save(\IWD\PaypalPos\Api\Data\UserLoggerInterface $object);

    /**
     * Get object by id
     * @param int $id
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface
     */
    public function getById($id);

    /**
     * Get by UserId value
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface
     */
    public function getByUserId($value);

    /**
     * Get by Email value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface
     */
    public function getByEmail($value);

    /**
     * Get by CreatedAt value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface
     */
    public function getByCreatedAt($value);

    /**
     * Delete object
     * @param \IWD\PaypalPos\Api\Data\UserLoggerInterface $object
     * @return boolean
     */
    public function delete(\IWD\PaypalPos\Api\Data\UserLoggerInterface $object);

    /**
     * Get a list of object
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \IWD\PaypalPos\Api\Data\UserLoggerSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
