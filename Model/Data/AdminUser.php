<?php

namespace IWD\PaypalPos\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;

class AdminUser extends AbstractExtensibleObject implements
    \IWD\PaypalPos\Api\Data\AdminUserInterface
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
    public function getFirstname()
    {
        return $this->_get(self::FIRSTNAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstname($value)
    {
        $this->setData(self::FIRSTNAME, $value);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastname()
    {
        return $this->_get(self::LASTNAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setLastname($value)
    {
        $this->setData(self::LASTNAME, $value);
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
    public function getUsername()
    {
        return $this->_get(self::USERNAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername($value)
    {
        $this->setData(self::USERNAME, $value);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreated()
    {
        return $this->_get(self::CREATED);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreated($value)
    {
        $this->setData(self::CREATED, $value);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getModified()
    {
        return $this->_get(self::MODIFIED);
    }

    /**
     * {@inheritdoc}
     */
    public function setModified($value)
    {
        $this->setData(self::MODIFIED, $value);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIsActive()
    {
        return $this->_get(self::IS_ACTIVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsActive($value)
    {
        $this->setData(self::IS_ACTIVE, $value);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaypalPassword()
    {
        return $this->_get(self::PAYPAL_PASSWORD);
    }

    /**
     * {@inheritdoc}
     */
    public function setPaypalPassword($value)
    {
        $this->setData(self::PAYPAL_PASSWORD, $value);
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
        \IWD\PaypalPos\Api\Data\AdminUserExtensionInterface $extensionAttributes
    ) {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
