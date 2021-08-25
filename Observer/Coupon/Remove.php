<?php
namespace IWD\PaypalPos\Observer\Coupon;

use Magento\Framework\Event\ObserverInterface;
use IWD\PaypalPos\Api\SalesCouponLoggerRepositoryInterface;
use IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface;
use IWD\PaypalPos\Api\Data\SalesCouponLoggerInterfaceFactory;

class Remove implements ObserverInterface
{
    /**
     * @var SalesCouponLoggerRepositoryInterface
     */
    private $couponLoggerRepository;
    /**
     * @var SalesCouponLoggerInterfaceFactory
     */
    private $couponLogger;

    public function __construct(
        SalesCouponLoggerRepositoryInterface $couponLoggerRepository,
        SalesCouponLoggerInterfaceFactory $couponLogger
    ) {
        $this->couponLoggerRepository = $couponLoggerRepository;
        $this->couponLogger = $couponLogger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $coupon = $observer->getEvent()->getCoupon();
        $logger = $this->couponLogger->create();
        $logger->setCouponId($coupon->getId());
        $logger->setRuleId($coupon->getRuleId());
        try {
            $this->couponLoggerRepository->save($logger);
            // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
        } catch (\Exception $e) {

        }
        return $this;
    }
}
