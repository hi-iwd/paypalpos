<?php

namespace IWD\PaypalPos\Api\Data;

interface UserLoggerInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const ID = 'entity_id';
    const USER_ID = 'user_id';
    const EMAIL = 'email';
    const USERNAME = 'username';
    const CREATED_AT = 'created_at';

    /**
     * Get value for entity_id
     * @return int
     */
    public function getId();

    /**
     * Set value for entity_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface
     */
    public function setId($value);

    /**
     * Get value for user_id
     * @return int
     */
    public function getUserId();

    /**
     * Set value for user_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface
     */
    public function setUserId($value);

    /**
     * Get value for email
     * @return string
     */
    public function getEmail();

    /**
     * Set value for email
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface
     */
    public function setEmail($value);

    /**
     * Get value for username
     * @return string
     */
    public function getUsername();

    /**
     * Set value for username
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface
     */
    public function setUsername($value);

    /**
     * Get value for created_at
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set value for created_at
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface
     */
    public function setCreatedAt($value);

    /**
     * Retrieve existing extension attributes object or create a new one
     * @return \IWD\PaypalPos\Api\Data\UserLoggerExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     * @param \IWD\PaypalPos\Api\Data\UserLoggerExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \IWD\PaypalPos\Api\Data\UserLoggerExtensionInterface $extensionAttributes
    );
}
