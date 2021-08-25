<?php

namespace IWD\PaypalPos\Model;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class CouponLoggerRegistry
{
    private $registry = [];
    private $registryByKey = [
        'rule_id' => [],
        'is_used' => [],
    ];

    /**
     * @var \IWD\PaypalPos\Model\CouponLoggerFactory
     */
    private $couponLoggerFactory;

    public function __construct(
        \IWD\PaypalPos\Model\CouponLoggerFactory $couponLoggerFactory
    ) {
        $this->couponLoggerFactory = $couponLoggerFactory;
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
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerInterface|null
     */
    public function retrieveById($id)
    {
        if (isset($this->registry[$id])) {
            return $this->registry[$id];
        }

        return null;
    }

    /**
     * Retrieve by RuleId value
     * @param int $value
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerInterface|null
     */
    public function retrieveByRuleId($value)
    {
        if (isset($this->registryByKey['rule_id'][$value])) {
            return $this->retrieveById($this->registryByKey['rule_id'][$value]);
        }

        return null;
    }

    /**
     * Retrieve by IsUsed value
     * @param string $value
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerInterface|null
     */
    public function retrieveByIsUsed($value)
    {
        if (isset($this->registryByKey['is_used'][$value])) {
            return $this->retrieveById($this->registryByKey['is_used'][$value]);
        }

        return null;
    }

    /**
     * Push one object into registry
     * @param \IWD\PaypalPos\Model\CouponLogger $couponLogger
     */
    public function push(\IWD\PaypalPos\Model\CouponLogger $couponLogger)
    {
        $this->registry[$couponLogger->getId()] = $couponLogger->getDataModel();
        foreach (array_keys($this->registryByKey) as $key) {
            $this->registryByKey[$key][$couponLogger->getData($key)] = $couponLogger->getId();
        }
    }
}
