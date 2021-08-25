<?php

namespace IWD\PaypalPos\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;

class SalesCouponLogger extends AbstractExtensibleObject implements
    \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($value)
    {
        $this->setData(self::ID, $value);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCouponId()
    {
        return $this->_get(self::COUPON_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCouponId($value)
    {
        $this->setData(self::COUPON_ID, $value);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRuleId()
    {
        return $this->_get(self::RULE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setRuleId($value)
    {
        $this->setData(self::RULE_ID, $value);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($value)
    {
        $this->setData(self::CREATED_AT, $value);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_get(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \IWD\PaypalPos\Api\Data\SalesCouponLoggerExtensionInterface $extensionAttributes
    ) {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
