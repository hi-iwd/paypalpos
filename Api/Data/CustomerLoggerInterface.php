<?php

namespace IWD\PaypalPos\Api\Data;

interface CustomerLoggerInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const ID = 'entity_id';
    const EMAIL = 'email';
    const CUSTOMER_ID = 'customer_id';
    const WEBSITE_ID = 'website_id';
    const CREATED_AT = 'created_at';

    /**
     * Get value for entity_id
     * @return int
     */
    public function getId();

    /**
     * Set value for entity_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerInterface
     */
    public function setId($value);

    /**
     * Get value for email
     * @return string
     */
    public function getEmail();

    /**
     * Set value for email
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerInterface
     */
    public function setEmail($value);

    /**
     * Get value for customer_id
     * @return string
     */
    public function getCustomerId();

    /**
     * Set value for customer_id
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerInterface
     */
    public function setCustomerId($value);

    /**
     * Get value for website_id
     * @return string
     */
    public function getWebsiteId();

    /**
     * Set value for website_id
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerInterface
     */
    public function setWebsiteId($value);

    /**
     * Get value for created_at
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set value for created_at
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerInterface
     */
    public function setCreatedAt($value);

    /**
     * Retrieve existing extension attributes object or create a new one
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     * @param \IWD\PaypalPos\Api\Data\CustomerLoggerExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \IWD\PaypalPos\Api\Data\CustomerLoggerExtensionInterface $extensionAttributes
    );
}
