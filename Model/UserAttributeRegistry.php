<?php

namespace IWD\PaypalPos\Model;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class UserAttributeRegistry
{
    private $registry = [];
    private $registryByKey = [
        'order_id' => [],
    ];

    /**
     * @var \IWD\PaypalPos\Model\UserAttributeFactory
     */
    private $userAttributeFactory;

    public function __construct(
        \IWD\PaypalPos\Model\UserAttributeFactory $userAttributeFactory
    ) {
        $this->userAttributeFactory = $userAttributeFactory;
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
     * @return \IWD\PaypalPos\Api\Data\UserAttributeInterface|null
     */
    public function retrieveById($id)
    {
        if (isset($this->registry[$id])) {
            return $this->registry[$id];
        }

        return null;
    }

    /**
     * Retrieve by OrderId value
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\UserAttributeInterface|null
     */
    public function retrieveByOrderId($value)
    {
        if (isset($this->registryByKey['order_id'][$value])) {
            return $this->retrieveById($this->registryByKey['order_id'][$value]);
        }

        return null;
    }

    /**
     * Push one object into registry
     * @param \IWD\PaypalPos\Model\UserAttribute $userAttribute
     */
    public function push(\IWD\PaypalPos\Model\UserAttribute $userAttribute)
    {
        $this->registry[$userAttribute->getId()] = $userAttribute->getDataModel();
        foreach (array_keys($this->registryByKey) as $key) {
            $this->registryByKey[$key][$userAttribute->getData($key)] = $userAttribute->getId();
        }
    }
}
