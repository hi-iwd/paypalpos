<?php

namespace IWD\PaypalPos\Model\Order;

use IWD\PaypalPos\Api\Data\BundleOrderItemOptionValueInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class BundleOrderItemOptionValue extends AbstractSimpleObject implements BundleOrderItemOptionValueInterface
{

    public function getTitle()
    {
        return $this->_get('title');
    }

    public function setTitle($title)
    {
        return $this->setData('title', $title);
    }

    public function getQty()
    {
        return $this->_get('qty');
    }

    public function setQty($qty)
    {
        return $this->setData('qty', $qty);
    }

    public function setPrice($price)
    {
        return $this->setData('price', $price);
    }

    public function getPrice()
    {
        return $this->_get('price');
    }
}
