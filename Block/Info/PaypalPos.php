<?php

namespace IWD\PaypalPos\Block\Info;

class PaypalPos extends \Magento\Payment\Block\Info
{
    /**
     * @var string
     */
    protected $_template = 'IWD_PaypalPos::info/paypalpos.phtml';

    /**
     * @return string
     */
    public function toPdf()
    {
        $this->setTemplate('IWD_PaypalPos::info/pdf/paypalpos.phtml');
        return $this->toHtml();
    }
}
