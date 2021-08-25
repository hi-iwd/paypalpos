<?php

namespace IWD\PaypalPos\Api\Data;

interface UserAttributeInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const ID = 'entity_id';
    const USER_ID = 'user_id';
    const ORDER_ID = 'order_id';

    /**
     * Get value for entity_id
     * @return int
     */
    public function getId();

    /**
     * Set value for entity_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\UserAttributeInterface
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
     * @return \IWD\PaypalPos\Api\Data\UserAttributeInterface
     */
    public function setUserId($value);

    /**
     * Get value for order_id
     * @return int
     */
    public function getOrderId();

    /**
     * Set value for order_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\UserAttributeInterface
     */
    public function setOrderId($value);

    /**
     * Retrieve existing extension attributes object or create a new one
     * @return \IWD\PaypalPos\Api\Data\UserAttributeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     * @param \IWD\PaypalPos\Api\Data\UserAttributeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \IWD\PaypalPos\Api\Data\UserAttributeExtensionInterface $extensionAttributes
    );
}
