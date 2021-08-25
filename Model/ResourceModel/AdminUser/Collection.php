<?php

namespace IWD\PaypalPos\Model\ResourceModel\AdminUser;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'user_id';

    protected function _construct()
    {
        $this->_init(
            \IWD\PaypalPos\Model\AdminUser::class,
            \IWD\PaypalPos\Model\ResourceModel\AdminUser::class
        );
    }
}
