<?php

namespace IWD\PaypalPos\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;

class CustomerLogger extends AbstractExtensibleObject implements
    \IWD\PaypalPos\Api\Data\CustomerLoggerInterface
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
    public function getEmail()
    {
        return $this->_get(self::EMAIL);
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($value)
    {
        $this->setData(self::EMAIL, $value);
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function getCustomerId()
    {
        return $this->_get(self::CUSTOMER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerId($value)
    {
        $this->setData(self::CUSTOMER_ID, $value);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getWebsiteId()
    {
        return $this->_get(self::WEBSITE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setWebsiteId($value)
    {
        $this->setData(self::WEBSITE_ID, $value);
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
        \IWD\PaypalPos\Api\Data\CustomerLoggerExtensionInterface $extensionAttributes
    ) {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
