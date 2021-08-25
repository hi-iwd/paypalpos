<?php

namespace IWD\PaypalPos\Model;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class UserLoggerRegistry
{
    private $registry = [];
    private $registryByKey = [
        'user_id' => [],
        'email' => [],
        'created_at' => [],
    ];

    /**
     * @var \IWD\PaypalPos\Model\UserLoggerFactory
     */
    private $userLoggerFactory;

    public function __construct(
        \IWD\PaypalPos\Model\UserLoggerFactory $userLoggerFactory
    ) {
        $this->userLoggerFactory = $userLoggerFactory;
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
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface|null
     */
    public function retrieveById($id)
    {
        if (isset($this->registry[$id])) {
            return $this->registry[$id];
        }

        return null;
    }

    /**
     * Retrieve by UserId value
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface|null
     */
    public function retrieveByUserId($value)
    {
        if (isset($this->registryByKey['user_id'][$value])) {
            return $this->retrieveById($this->registryByKey['user_id'][$value]);
        }

        return null;
    }

    /**
     * Retrieve by Email value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface|null
     */
    public function retrieveByEmail($value)
    {
        if (isset($this->registryByKey['email'][$value])) {
            return $this->retrieveById($this->registryByKey['email'][$value]);
        }

        return null;
    }

    /**
     * Retrieve by CreatedAt value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface|null
     */
    public function retrieveByCreatedAt($value)
    {
        if (isset($this->registryByKey['created_at'][$value])) {
            return $this->retrieveById($this->registryByKey['created_at'][$value]);
        }

        return null;
    }

    /**
     * Push one object into registry
     * @param \IWD\PaypalPos\Model\UserLogger $userLogger
     */
    public function push(\IWD\PaypalPos\Model\UserLogger $userLogger)
    {
        $this->registry[$userLogger->getId()] = $userLogger->getDataModel();
        foreach (array_keys($this->registryByKey) as $key) {
            $this->registryByKey[$key][$userLogger->getData($key)] = $userLogger->getId();
        }
    }
}
