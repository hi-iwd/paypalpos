<?php

namespace IWD\PaypalPos\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class AdminUser extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('admin_user', 'user_id');
    }
}
