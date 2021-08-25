<?php

namespace IWD\PaypalPos\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;

class UserAttribute extends AbstractExtensibleObject implements
    \IWD\PaypalPos\Api\Data\UserAttributeInterface
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
    public function getUserId()
    {
        return $this->_get(self::USER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setUserId($value)
    {
        $this->setData(self::USER_ID, $value);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderId()
    {
        return $this->_get(self::ORDER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderId($value)
    {
        $this->setData(self::ORDER_ID, $value);
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
        \IWD\PaypalPos\Api\Data\UserAttributeExtensionInterface $extensionAttributes
    ) {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
