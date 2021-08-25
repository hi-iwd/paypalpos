<?php

namespace IWD\PaypalPos\Api\Data;

interface SalesCouponLoggerInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const ID = 'entity_id';
    const COUPON_ID = 'coupon_id';
    const RULE_ID = 'rule_id';
    const CREATED_AT = 'created_at';

    /**
     * Get value for entity_id
     * @return int
     */
    public function getId();

    /**
     * Set value for entity_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface
     */
    public function setId($value);

    /**
     * Get value for coupon_id
     * @return int
     */
    public function getCouponId();

    /**
     * Set value for coupon_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface
     */
    public function setCouponId($value);

    /**
     * Get value for rule_id
     * @return int
     */
    public function getRuleId();

    /**
     * Set value for rule_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface
     */
    public function setRuleId($value);

    /**
     * Get value for created_at
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set value for created_at
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface
     */
    public function setCreatedAt($value);

    /**
     * Retrieve existing extension attributes object or create a new one
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     * @param \IWD\PaypalPos\Api\Data\SalesCouponLoggerExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \IWD\PaypalPos\Api\Data\SalesCouponLoggerExtensionInterface $extensionAttributes
    );
}
