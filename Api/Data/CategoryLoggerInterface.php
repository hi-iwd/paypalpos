<?php

namespace IWD\PaypalPos\Api\Data;

interface CategoryLoggerInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const ID = 'entity_id';
    const CATEGORY_ID = 'category_id';
    const STORE_ID = 'store_id';
    const CREATED_AT = 'created_at';

    /**
     * Get value for entity_id
     * @return int
     */
    public function getId();

    /**
     * Set value for entity_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface
     */
    public function setId($value);

    /**
     * Get value for category_id
     * @return int
     */
    public function getCategoryId();

    /**
     * Set value for category_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface
     */
    public function setCategoryId($value);

    /**
     * Get value for store_id
     * @return string
     */
    public function getStoreId();

    /**
     * Set value for store_id
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface
     */
    public function setStoreId($value);

    /**
     * Get value for created_at
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set value for created_at
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface
     */
    public function setCreatedAt($value);

    /**
     * Retrieve existing extension attributes object or create a new one
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     * @param \IWD\PaypalPos\Api\Data\CategoryLoggerExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \IWD\PaypalPos\Api\Data\CategoryLoggerExtensionInterface $extensionAttributes
    );
}
