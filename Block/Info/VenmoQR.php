<?php

namespace IWD\PaypalPos\Block\Info;

class VenmoQR extends \Magento\Payment\Block\Info
{
    /**
     * @var string
     */
    protected $_template = 'IWD_PaypalPos::info/venmoqr.phtml';

    /**
     * @return string
     */
    public function toPdf()
    {
        $this->setTemplate('IWD_PaypalPos::info/pdf/venmoqr.phtml');
        return $this->toHtml();
    }
}
