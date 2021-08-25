<?php

namespace IWD\PaypalPos\Model;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class CategoryLoggerRegistry
{
    private $registry = [];
    private $registryByKey = [
        'store_id' => [],
        'category_id' => [],
        'created_at' => [],
    ];

    /**
     * @var \IWD\PaypalPos\Model\CategoryLoggerFactory
     */
    private $categoryLoggerFactory;

    public function __construct(
        \IWD\PaypalPos\Model\CategoryLoggerFactory $categoryLoggerFactory
    ) {
        $this->categoryLoggerFactory = $categoryLoggerFactory;
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
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface|null
     */
    public function retrieveById($id)
    {
        if (isset($this->registry[$id])) {
            return $this->registry[$id];
        }

        return null;
    }

    /**
     * Retrieve by StoreId value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface|null
     */
    public function retrieveByStoreId($value)
    {
        if (isset($this->registryByKey['store_id'][$value])) {
            return $this->retrieveById($this->registryByKey['store_id'][$value]);
        }

        return null;
    }

    /**
     * Retrieve by CategoryId value
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface|null
     */
    public function retrieveByCategoryId($value)
    {
        if (isset($this->registryByKey['category_id'][$value])) {
            return $this->retrieveById($this->registryByKey['category_id'][$value]);
        }

        return null;
    }

    /**
     * Retrieve by CreatedAt value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface|null
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
     * @param \IWD\PaypalPos\Model\CategoryLogger $categoryLogger
     */
    public function push(\IWD\PaypalPos\Model\CategoryLogger $categoryLogger)
    {
        $this->registry[$categoryLogger->getId()] = $categoryLogger->getDataModel();
        foreach (array_keys($this->registryByKey) as $key) {
            $this->registryByKey[$key][$categoryLogger->getData($key)] = $categoryLogger->getId();
        }
    }
}
