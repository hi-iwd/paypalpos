<?php

namespace IWD\PaypalPos\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class UserAttribute extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('paypal_pos_order_user', 'entity_id');
    }
}
