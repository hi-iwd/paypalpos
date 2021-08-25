<?php

namespace IWD\PaypalPos\Model\Customer\Address;

use Magento\Framework\App\Config\ScopeConfigInterface;

class DefaultAddress
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * DefaultAddress constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return array
     */
    public function get()
    {
        $address = [];
        $address['country_id'] = $this->scopeConfig->getValue('iwd/settings/country_id');
        $address['region_id'] = $this->scopeConfig->getValue('iwd/settings/region_id');
        $address['postcode'] = $this->scopeConfig->getValue('iwd/settings/postcode');
        $address['city'] = $this->scopeConfig->getValue('iwd/settings/city');
        $address['street'] = $this->scopeConfig->getValue('iwd/settings/street_line1');
        $address['street'] = $address['street'].$this->scopeConfig->getValue('iwd/settings/street_line2');
        $address['email'] = $this->scopeConfig->getValue('iwd/settings/email');
        $address['same_as_billing'] = 1;
        $address['telephone'] = $this->scopeConfig->getValue('iwd/settings/telephone');
        $address['firstname'] = $this->scopeConfig->getValue('iwd/settings/firstname');
        $address['lastname'] = $this->scopeConfig->getValue('iwd/settings/lastname');
        $address['save_in_address_book'] = 0;
        return $address;
    }
}
