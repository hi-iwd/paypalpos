<?php

namespace IWD\PaypalPos\Api\Data;

interface AdminUserInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const ID = 'user_id';
    const FIRSTNAME = 'firstname';
    const LASTNAME = 'lastname';
    const EMAIL = 'email';
    const USERNAME = 'username';
    const CREATED = 'created';
    const MODIFIED = 'modified';
    const IS_ACTIVE = 'is_active';
    const PAYPAL_PASSWORD = 'paypal_password';

    /**
     * Get value for user_id
     * @return int
     */
    public function getId();

    /**
     * Set value for user_id
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface
     */
    public function setId($value);

    /**
     * Get value for firstname
     * @return string
     */
    public function getFirstname();

    /**
     * Set value for firstname
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface
     */
    public function setFirstname($value);

    /**
     * Get value for lastname
     * @return string
     */
    public function getLastname();

    /**
     * Set value for lastname
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface
     */
    public function setLastname($value);

    /**
     * Get value for email
     * @return string
     */
    public function getEmail();

    /**
     * Set value for email
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface
     */
    public function setEmail($value);

    /**
     * Get value for username
     * @return string
     */
    public function getUsername();

    /**
     * Set value for username
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface
     */
    public function setUsername($value);

    /**
     * Get value for created
     * @return string
     */
    public function getCreated();

    /**
     * Set value for created
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface
     */
    public function setCreated($value);

    /**
     * Get value for modified
     * @return string
     */
    public function getModified();

    /**
     * Set value for modified
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface
     */
    public function setModified($value);

    /**
     * Get value for is_active
     * @return string
     */
    public function getIsActive();

    /**
     * Set value for is_active
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface
     */
    public function setIsActive($value);

    /**
     * Get value for paypal_password
     * @return string
     */
    public function getPaypalPassword();

    /**
     * Set value for paypal_password
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface
     */
    public function setPaypalPassword($value);

    /**
     * Retrieve existing extension attributes object or create a new one
     * @return \IWD\PaypalPos\Api\Data\AdminUserExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     * @param \IWD\PaypalPos\Api\Data\AdminUserExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \IWD\PaypalPos\Api\Data\AdminUserExtensionInterface $extensionAttributes
    );
}
