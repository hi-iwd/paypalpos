<?php

namespace IWD\PaypalPos\Model\CartPriceRule;

use Magento\SalesRule\Api\Data\RuleInterface;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory as CustomerGroupCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class CartPriceRuleData
{
    private $data = [
        'name' => 'PayPal POS',
        'is_advanced' => true,
        'stop_rules_processing' => false,
        'discount_qty' => 0,
        'customer_group_ids' => [],
        'website_ids' => [],
        'coupon_type' => RuleInterface::COUPON_TYPE_SPECIFIC_COUPON,
        'simple_action' => '',
        'is_active' => true,
        'discount_amount' => 0
    ];
    /**
     * @var CustomerGroupCollectionFactory
     */
    private $customerGroupCollectionFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * CartPriceRuleData constructor.
     * @param CustomerGroupCollectionFactory $customerGroupCollectionFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CustomerGroupCollectionFactory $customerGroupCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->customerGroupCollectionFactory = $customerGroupCollectionFactory;
        $this->storeManager = $storeManager;
        $this->setCustomerGroupIds();
        $this->setWebsiteIds();
    }

    /**
     * @return array
     */
    public function getRuleData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function setPercentType()
    {
        $this->data['simple_action'] = RuleInterface::DISCOUNT_ACTION_BY_PERCENT;
        return $this->data;
    }

    /**
     * @return array
     */
    public function setFixedType()
    {
        $this->data['simple_action'] = RuleInterface::DISCOUNT_ACTION_FIXED_AMOUNT_FOR_CART;
        return $this->data;
    }

    /**
     * @param $amount
     * @return array
     */
    public function setDiscountAmount($amount)
    {
        $this->data['discount_amount'] = $amount;
        return $this->data;
    }

    /**
     * set customer group ids
     */
    private function setCustomerGroupIds()
    {
        $this->data['customer_group_ids'] = $this->getAvailableCustomerGroupIds();
    }

    private function setWebsiteIds()
    {
        $this->data['website_ids'] = $this->getAvailableWebsiteIds();
    }

    /**
     * @return mixed
     */
    private function getAvailableCustomerGroupIds()
    {
        /** @var CustomerGroupCollection $collection */
        $collection = $this->customerGroupCollectionFactory->create();
        $collection->addFieldToSelect('customer_group_id');
        return $collection->getAllIds();
    }

    /**
     * @return array
     */
    private function getAvailableWebsiteIds()
    {
        $websiteIds = [];
        $websites = $this->storeManager->getWebsites();
        foreach ($websites as $website) {
            $websiteIds[] = $website->getId();
        }

        return $websiteIds;
    }
}
