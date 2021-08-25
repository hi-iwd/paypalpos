<?php

namespace IWD\PaypalPos\Model\Order;

use IWD\PaypalPos\Api\Data\BundleOrderItemOptionInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class BundleOrderItemOption extends AbstractSimpleObject implements BundleOrderItemOptionInterface
{
    public function getOptionId()
    {
        return $this->_get('option_id');
    }

    public function setOptionId($optionId)
    {
        return $this->setData('option_id', $optionId);
    }

    public function getLabel()
    {
        return $this->_get('label');
    }

    public function setLabel($label)
    {
        return $this->setData('label', $label);
    }

    public function setValue($value)
    {
        return $this->setData('value', $value);
    }

    public function getValue()
    {
        return $this->_get('value');
    }
}
