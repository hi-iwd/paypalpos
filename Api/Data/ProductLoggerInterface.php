<?php

namespace IWD\PaypalPos\Api\Data;

interface ProductLoggerInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const ID = 'entity_id';
    const PRODUCT_ID = 'product_id';
    const CREATED_AT = 'created_at';

    /**
     * Get value for entity_id
     * @return int
     */
    public function getId();

    /**
     * Set value for entity_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\ProductLoggerInterface
     */
    public function setId($value);

    /**
     * Get value for product_id
     * @return int
     */
    public function getProductId();

    /**
     * Set value for product_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\ProductLoggerInterface
     */
    public function setProductId($value);

    /**
     * Get value for created_at
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set value for created_at
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\ProductLoggerInterface
     */
    public function setCreatedAt($value);

    /**
     * Retrieve existing extension attributes object or create a new one
     * @return \IWD\PaypalPos\Api\Data\ProductLoggerExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     * @param \IWD\PaypalPos\Api\Data\ProductLoggerExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \IWD\PaypalPos\Api\Data\ProductLoggerExtensionInterface $extensionAttributes
    );
}
