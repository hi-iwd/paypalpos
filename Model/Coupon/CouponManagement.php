<?php

namespace IWD\PaypalPos\Model\Coupon;

use Magento\SalesRule\Api\Data\CouponInterfaceFactory;
use Magento\SalesRule\Api\CouponRepositoryInterface;
use Magento\Framework\Math\Random;

class CouponManagement
{
    /**
     * @var CouponInterfaceFactory
     */
    private $couponFactory;
    /**
     * @var CouponRepositoryInterface
     */
    private $couponRepository;
    /**
     * @var Random
     */
    private $random;

    /**
     * CouponManagement constructor.
     * @param CouponInterfaceFactory $couponFactory
     * @param CouponRepositoryInterface $couponRepository
     * @param Random $random
     */
    public function __construct(
        CouponInterfaceFactory $couponFactory,
        CouponRepositoryInterface $couponRepository,
        Random $random
    ) {
        $this->couponFactory = $couponFactory;
        $this->couponRepository = $couponRepository;
        $this->random = $random;
    }

    /**
     * @param \Magento\SalesRule\Api\Data\RuleInterface $cartPriceRule
     * @return \Magento\SalesRule\Api\Data\CouponInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createCoupon($cartPriceRule)
    {
        $coupon = $this->couponFactory->create();
        $coupon->setCode($this->random->getRandomString(32))
            ->setRuleId($cartPriceRule->getRuleId())
            ->setIsPrimary(true);

        return $this->couponRepository->save($coupon);
    }
}
