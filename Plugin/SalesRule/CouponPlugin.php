<?php

namespace IWD\PaypalPos\Plugin\SalesRule;

use IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface;
use IWD\PaypalPos\Api\Data\SalesCouponLoggerInterfaceFactory;
use IWD\PaypalPos\Api\SalesCouponLoggerRepositoryInterface;

class CouponPlugin
{
    /**
     * @var SalesCouponLoggerInterfaceFactory
     */
    private $couponLoggerFactory;
    /**
     * @var SalesCouponLoggerRepositoryInterface
     */
    private $couponLoggerRepository;

    /**
     * CouponPlugin constructor.
     * @param SalesCouponLoggerInterfaceFactory $couponLoggerFactory
     * @param SalesCouponLoggerRepositoryInterface $couponLoggerRepository
     */
    public function __construct(
        SalesCouponLoggerInterfaceFactory $couponLoggerFactory,
        SalesCouponLoggerRepositoryInterface $couponLoggerRepository
    ) {
        $this->couponLoggerFactory = $couponLoggerFactory;
        $this->couponLoggerRepository = $couponLoggerRepository;
    }

    /**
     * @param \Magento\SalesRule\Model\Coupon $subject
     * @param callable $proceed
     * @return mixed
     */
    public function aroundDelete(\Magento\SalesRule\Model\Coupon $subject, callable $proceed)
    {
        $couponLogger = $this->couponLoggerFactory->create();
        $couponLogger->setCouponId($subject->getId());
        $couponLogger->setRuleId($subject->getRuleId());
        $result = $proceed();
        $this->couponLoggerRepository->save($couponLogger);

        return $result;
    }
}
