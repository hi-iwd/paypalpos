<?php

namespace IWD\PaypalPos\Api\Data;

interface CouponLoggerInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const ID = 'entity_id';
    const RULE_ID = 'rule_id';
    const IS_USED = 'is_used';

    /**
     * Get value for entity_id
     * @return int
     */
    public function getId();

    /**
     * Set value for entity_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerInterface
     */
    public function setId($value);

    /**
     * Get value for rule_id
     * @return int
     */
    public function getRuleId();

    /**
     * Set value for rule_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerInterface
     */
    public function setRuleId($value);

    /**
     * Get value for is_used
     * @return string
     */
    public function getIsUsed();

    /**
     * Set value for is_used
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerInterface
     */
    public function setIsUsed($value);

    /**
     * Retrieve existing extension attributes object or create a new one
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     * @param \IWD\PaypalPos\Api\Data\CouponLoggerExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \IWD\PaypalPos\Api\Data\CouponLoggerExtensionInterface $extensionAttributes
    );
}
