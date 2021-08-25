<?php

namespace IWD\PaypalPos\Model;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class CustomerLoggerRegistry
{
    private $registry = [];
    private $registryByKey = [
        'email' => [],
    ];

    /**
     * @var \IWD\PaypalPos\Model\CustomerLoggerFactory
     */
    private $customerLoggerFactory;

    public function __construct(
        \IWD\PaypalPos\Model\CustomerLoggerFactory $customerLoggerFactory
    ) {
        $this->customerLoggerFactory = $customerLoggerFactory;
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
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerInterface|null
     */
    public function retrieveById($id)
    {
        if (isset($this->registry[$id])) {
            return $this->registry[$id];
        }

        return null;
    }

    /**
     * Retrieve by Email value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerInterface|null
     */
    public function retrieveByEmail($value)
    {
        if (isset($this->registryByKey['email'][$value])) {
            return $this->retrieveById($this->registryByKey['email'][$value]);
        }

        return null;
    }

    /**
     * Push one object into registry
     * @param \IWD\PaypalPos\Model\CustomerLogger $customerLogger
     */
    public function push(\IWD\PaypalPos\Model\CustomerLogger $customerLogger)
    {
        $this->registry[$customerLogger->getId()] = $customerLogger->getDataModel();
        foreach (array_keys($this->registryByKey) as $key) {
            $this->registryByKey[$key][$customerLogger->getData($key)] = $customerLogger->getId();
        }
    }
}
