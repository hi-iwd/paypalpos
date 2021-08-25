<?php

namespace IWD\PaypalPos\Model;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class AdminUserRegistry
{
    private $registry = [];
    private $registryByKey = [
        'username' => [],
    ];

    /**
     * @var \IWD\PaypalPos\Model\AdminUserFactory
     */
    private $adminUserFactory;

    public function __construct(
        \IWD\PaypalPos\Model\AdminUserFactory $adminUserFactory
    ) {
        $this->adminUserFactory = $adminUserFactory;
    }

    /**
     * Remove registry entity by id
     * @param int $id
     */
    public function removeById($id)
    {
        if (isset($this->registry[$id])) {
            unset($this->registry[$id]);
        }

        foreach (array_keys($this->registryByKey) as $key) {
            $reverseMap = array_flip($this->registryByKey[$key]);
            if (isset($reverseMap[$id])) {
                unset($this->registryByKey[$key][$reverseMap[$id]]);
            }
        }
    }

    /**
     * Clear all registry entries
     */
    public function clear()
    {
        $this->registry = [];
        foreach (array_keys($this->registryByKey) as $key) {
            $this->registryByKey[$key] = [];
        }
    }

    /**
     * Push one object into registry
     * @param int $id
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface|null
     */
    public function retrieveById($id)
    {
        if (isset($this->registry[$id])) {
            return $this->registry[$id];
        }

        return null;
    }

    /**
     * Retrieve by Username value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface|null
     */
    public function retrieveByUsername($value)
    {
        if (isset($this->registryByKey['username'][$value])) {
            return $this->retrieveById($this->registryByKey['username'][$value]);
        }

        return null;
    }

    /**
     * Push one object into registry
     * @param \IWD\PaypalPos\Model\AdminUser $adminUser
     */
    public function push(\IWD\PaypalPos\Model\AdminUser $adminUser)
    {
        $this->registry[$adminUser->getId()] = $adminUser->getDataModel();
        foreach (array_keys($this->registryByKey) as $key) {
            $this->registryByKey[$key][$adminUser->getData($key)] = $adminUser->getId();
        }
    }
}
