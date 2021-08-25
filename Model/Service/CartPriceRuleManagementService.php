<?php

namespace IWD\PaypalPos\Model\Service;

use IWD\PaypalPos\Api\CartPriceRuleManagementInterface;
use IWD\PaypalPos\Model\CartPriceRule\CartPriceRuleData;
use IWD\PaypalPos\Model\CartPriceRule\CartPriceRuleManagement;
use IWD\PaypalPos\Model\Coupon\CouponManagement;
use Magento\Quote\Api\CouponManagementInterface as QuoteCouponManagementInterface;
use IWD\PaypalPos\Api\Data\CouponLoggerInterface;
use IWD\PaypalPos\Api\Data\CouponLoggerInterfaceFactory;
use IWD\PaypalPos\Api\CouponLoggerRepositoryInterface;

class CartPriceRuleManagementService implements CartPriceRuleManagementInterface
{
    /**
     * @var CartPriceRuleData
     */
    private $cartPriceRuleData;
    /**
     * @var CartPriceRuleManagement
     */
    private $cartPriceRuleManagement;
    /**
     * @var CouponManagement
     */
    private $couponManagement;
    /**
     * @var QuoteCouponManagementInterface
     */
    private $quoteCouponManagement;
    /**
     * @var CouponLoggerInterfaceFactory
     */
    private $couponLoggerFactory;
    /**
     * @var CouponLoggerRepositoryInterface
     */
    private $couponLoggerRepository;

    /**
     * CartPriceRuleManagementService constructor.
     * @param CartPriceRuleData $cartPriceRuleData
     * @param CartPriceRuleManagement $cartPriceRuleManagement
     * @param CouponManagement $couponManagement
     * @param QuoteCouponManagementInterface $quoteCouponManagement
     * @param CouponLoggerInterfaceFactory $couponLoggerFactory
     * @param CouponLoggerRepositoryInterface $couponLoggerRepository
     */
    public function __construct(
        CartPriceRuleData $cartPriceRuleData,
        CartPriceRuleManagement $cartPriceRuleManagement,
        CouponManagement $couponManagement,
        QuoteCouponManagementInterface $quoteCouponManagement,
        CouponLoggerInterfaceFactory $couponLoggerFactory,
        CouponLoggerRepositoryInterface  $couponLoggerRepository
    ) {
        $this->cartPriceRuleData = $cartPriceRuleData;
        $this->cartPriceRuleManagement = $cartPriceRuleManagement;
        $this->couponManagement = $couponManagement;
        $this->quoteCouponManagement = $quoteCouponManagement;
        $this->couponLoggerFactory = $couponLoggerFactory;
        $this->couponLoggerRepository = $couponLoggerRepository;
    }

    /**
     * @param int $cartId
     * @param int $amountDiscount
     * @return mixed|string|null
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createPercent($cartId, $amountDiscount)
    {
        $this->cartPriceRuleData->setPercentType();
        return  $this->createRule($cartId, $amountDiscount);
    }

    /**
     * @param int $cartId
     * @param int $amountDiscount
     * @return mixed|string|null
     */
    public function createFixed($cartId, $amountDiscount)
    {
        $this->cartPriceRuleData->setFixedType();
        return  $this->createRule($cartId, $amountDiscount);
    }

    /**
     * @param $cartId
     * @param $amount
     * @return string|null
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createRule($cartId, $amount)
    {
        $cartPriceRuleData = $this->cartPriceRuleData->setDiscountAmount($amount);
        $cartPriceRule = $this->cartPriceRuleManagement->createRule($cartPriceRuleData);
        $coupon = $this->couponManagement->createCoupon($cartPriceRule);
        $this->quoteCouponManagement->set($cartId, $coupon->getCode());
        $couponLogger = $this->couponLoggerFactory->create();
        $couponLogger->setRuleId($cartPriceRule->getRuleId());
        $this->couponLoggerRepository->save($couponLogger);
        return $coupon->getCode();
    }
}
