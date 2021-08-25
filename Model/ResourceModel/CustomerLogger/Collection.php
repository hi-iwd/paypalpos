<?php

namespace IWD\PaypalPos\Model\ResourceModel\CustomerLogger;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(
            \IWD\PaypalPos\Model\CustomerLogger::class,
            \IWD\PaypalPos\Model\ResourceModel\CustomerLogger::class
        );
    }
}
