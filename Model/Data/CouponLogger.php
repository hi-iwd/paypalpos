<?php

namespace IWD\PaypalPos\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;

class CouponLogger extends AbstractExtensibleObject implements
    \IWD\PaypalPos\Api\Data\CouponLoggerInterface
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
    public function getIsUsed()
    {
        return $this->_get(self::IS_USED);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsUsed($value)
    {
        $this->setData(self::IS_USED, $value);
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
        \IWD\PaypalPos\Api\Data\CouponLoggerExtensionInterface $extensionAttributes
    ) {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
