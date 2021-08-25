<?php

namespace IWD\PaypalPos\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;

class CategoryLogger extends AbstractExtensibleObject implements
    \IWD\PaypalPos\Api\Data\CategoryLoggerInterface
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
    public function getCategoryId()
    {
        return $this->_get(self::CATEGORY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCategoryId($value)
    {
        $this->setData(self::CATEGORY_ID, $value);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        return $this->_get(self::STORE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId($value)
    {
        $this->setData(self::STORE_ID, $value);
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
        \IWD\PaypalPos\Api\Data\CategoryLoggerExtensionInterface $extensionAttributes
    ) {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
