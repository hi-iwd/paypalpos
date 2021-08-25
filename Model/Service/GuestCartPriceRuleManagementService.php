<?php

namespace IWD\PaypalPos\Model\Service;

use IWD\PaypalPos\Api\GuestCartPriceRuleManagementInterface;
use IWD\PaypalPos\Model\CartPriceRule\CartPriceRuleData;
use IWD\PaypalPos\Model\CartPriceRule\CartPriceRuleManagement;
use IWD\PaypalPos\Model\Coupon\CouponManagement;
use Magento\Quote\Api\CouponManagementInterface as QuoteCouponManagementInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;

class GuestCartPriceRuleManagementService implements GuestCartPriceRuleManagementInterface
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
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * CartPriceRuleManagementService constructor.
     * @param CartPriceRuleData $cartPriceRuleData
     * @param CartPriceRuleManagement $cartPriceRuleManagement
     * @param CouponManagement $couponManagement
     * @param QuoteCouponManagementInterface $quoteCouponManagement
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     */
    public function __construct(
        CartPriceRuleData $cartPriceRuleData,
        CartPriceRuleManagement $cartPriceRuleManagement,
        CouponManagement $couponManagement,
        QuoteCouponManagementInterface $quoteCouponManagement,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->cartPriceRuleData = $cartPriceRuleData;
        $this->cartPriceRuleManagement = $cartPriceRuleManagement;
        $this->couponManagement = $couponManagement;
        $this->quoteCouponManagement = $quoteCouponManagement;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * @param int $cartId
     * @param int $percentDiscount
     * @return mixed|string|null
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createPercent($cartId, $percentDiscount)
    {
        $this->cartPriceRuleData->setPercentType();
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return  $this->createRule($quoteIdMask->getQuoteId(), $percentDiscount);
    }

    /**
     * @param string $cartId
     * @param int $amountDiscount
     * @return mixed|string|null
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createFixed($cartId, $amountDiscount)
    {
        $this->cartPriceRuleData->setFixedType();
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return  $this->createRule($quoteIdMask->getQuoteId(), $amountDiscount);
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
        return $coupon->getCode();
    }
}
