<?php

namespace IWD\PaypalPos\Model\CartPriceRule;

use Magento\SalesRule\Api\Data\RuleInterfaceFactory;
use Magento\SalesRule\Api\RuleRepositoryInterface;

class CartPriceRuleManagement
{
    /**
     * @var RuleInterfaceFactory
     */
    private $cartPriceRuleFactory;
    /**
     * @var RuleRepositoryInterface
     */
    private $cartPriceRuleRepository;

    /**
     * CartPriceRuleManagement constructor.
     * @param RuleInterfaceFactory $cartPriceRuleFactory
     * @param RuleRepositoryInterface $cartPriceRuleRepository
     */
    public function __construct(
        RuleInterfaceFactory $cartPriceRuleFactory,
        RuleRepositoryInterface $cartPriceRuleRepository
    ) {
        $this->cartPriceRuleFactory = $cartPriceRuleFactory;
        $this->cartPriceRuleRepository = $cartPriceRuleRepository;
    }

    /**
     * @param array $ruleData
     * @return \Magento\SalesRule\Api\Data\RuleInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createRule($ruleData)
    {
        $cartPriceRule = $this->cartPriceRuleFactory->create();
        foreach ($ruleData as $key => $val) {
            $cartPriceRule->setData($key, $val);
        }

        return $this->cartPriceRuleRepository->save($cartPriceRule);
    }
}
