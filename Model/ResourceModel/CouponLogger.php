<?php

namespace IWD\PaypalPos\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class CouponLogger extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('paypal_pos_cart_rule', 'entity_id');
    }
}
