<?php

namespace IWD\PaypalPos\Plugin\SalesRule;

use Magento\SalesRule\Api\CouponRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger;

class RulePlugin
{
    /**
     * @var CouponRepositoryInterface
     */
    private $couponRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var SalesCouponLogger
     */
    private $salesCouponLogger;

    /**
     * RulePlugin constructor.
     * @param CouponRepositoryInterface $couponRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SalesCouponLogger $salesCouponLogger
     */
    public function __construct(
        CouponRepositoryInterface $couponRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SalesCouponLogger $salesCouponLogger
    ) {
        $this->couponRepository = $couponRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->salesCouponLogger = $salesCouponLogger;
    }

    /**
     * @param \Magento\SalesRule\Model\Rule $subject
     */
    public function beforeSave(\Magento\SalesRule\Model\Rule $subject)
    {
        $subject->setData('updated_at', null);
    }

    /**
     * @param \Magento\SalesRule\Model\Rule $subject
     * @param callable $proceed
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundDelete(\Magento\SalesRule\Model\Rule $subject, callable $proceed)
    {
        $ruleId = $subject->getId();
        $arr = [];
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('rule_id', $ruleId)
            ->create();
        $coupons = $this->couponRepository->getList($searchCriteria);
        $result = $proceed();

        foreach ($coupons->getItems() as $coupon) {
            $arr[] = ['coupon_id' => $coupon->getId(), 'rule_id' => $coupon->getRuleId()];
        }
        $this->salesCouponLogger->insertLogData($arr);
        return $result;
    }
}
