<?php

namespace IWD\PaypalPos\Model;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class ProductLoggerRegistry
{
    private $registry = [];
    private $registryByKey = [
        'product_id' => [],
    ];

    /**
     * @var \IWD\PaypalPos\Model\ProductLoggerFactory
     */
    private $productLoggerFactory;

    public function __construct(
        \IWD\PaypalPos\Model\ProductLoggerFactory $productLoggerFactory
    ) {
        $this->productLoggerFactory = $productLoggerFactory;
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
     * @return \IWD\PaypalPos\Api\Data\ProductLoggerInterface|null
     */
    public function retrieveById($id)
    {
        if (isset($this->registry[$id])) {
            return $this->registry[$id];
        }

        return null;
    }

    /**
     * Retrieve by ProductId value
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\ProductLoggerInterface|null
     */
    public function retrieveByProductId($value)
    {
        if (isset($this->registryByKey['product_id'][$value])) {
            return $this->retrieveById($this->registryByKey['product_id'][$value]);
        }

        return null;
    }

    /**
     * Push one object into registry
     * @param \IWD\PaypalPos\Model\ProductLogger $productLogger
     */
    public function push(\IWD\PaypalPos\Model\ProductLogger $productLogger)
    {
        $this->registry[$productLogger->getId()] = $productLogger->getDataModel();
        foreach (array_keys($this->registryByKey) as $key) {
            $this->registryByKey[$key][$productLogger->getData($key)] = $productLogger->getId();
        }
    }
}
