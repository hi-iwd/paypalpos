<?php

namespace IWD\PaypalPos\Model;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class SalesCouponLoggerRegistry
{
    private $registry = [];
    private $registryByKey = [
        'coupon_id' => [],
    ];

    /**
     * @var \IWD\PaypalPos\Model\SalesCouponLoggerFactory
     */
    private $salesCouponLoggerFactory;

    public function __construct(
        \IWD\PaypalPos\Model\SalesCouponLoggerFactory $salesCouponLoggerFactory
    ) {
        $this->salesCouponLoggerFactory = $salesCouponLoggerFactory;
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
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface|null
     */
    public function retrieveById($id)
    {
        if (isset($this->registry[$id])) {
            return $this->registry[$id];
        }

        return null;
    }

    /**
     * Retrieve by CouponId value
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface|null
     */
    public function retrieveByCouponId($value)
    {
        if (isset($this->registryByKey['coupon_id'][$value])) {
            return $this->retrieveById($this->registryByKey['coupon_id'][$value]);
        }

        return null;
    }

    /**
     * Push one object into registry
     * @param \IWD\PaypalPos\Model\SalesCouponLogger $salesCouponLogger
     */
    public function push(\IWD\PaypalPos\Model\SalesCouponLogger $salesCouponLogger)
    {
        $this->registry[$salesCouponLogger->getId()] = $salesCouponLogger->getDataModel();
        foreach (array_keys($this->registryByKey) as $key) {
            $this->registryByKey[$key][$salesCouponLogger->getData($key)] = $salesCouponLogger->getId();
        }
    }
}
