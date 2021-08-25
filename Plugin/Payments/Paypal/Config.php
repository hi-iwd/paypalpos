<?php

namespace IWD\PaypalPos\Plugin\Payments\Paypal;

use Magento\Paypal\Model\AbstractConfig as PaypalConfig;

class Config
{
    /**
     * @param PaypalConfig $subject
     * @param $results
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetBuildNotationCode(PaypalConfig $subject, $result)
    {
        return 'IWD_SP_PPHSDK';
    }
}
