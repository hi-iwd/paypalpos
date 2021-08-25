<?php

namespace IWD\PaypalPos\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Psr\Log\LoggerInterface;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class SalesCouponLogger extends AbstractDb
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * SalesCouponLogger constructor.
     * @param Context $context
     * @param LoggerInterface $logger
     * @param null $connectionName
     */
    public function __construct(Context $context, LoggerInterface $logger, $connectionName = null)
    {
        $this->logger = $logger;
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('paypal_pos_coupon_delete', 'entity_id');
    }

    public function insertLogData(array $data)
    {
        if (empty($data)) {
            return;
        }
        $connection = $this->getConnection();
        $connection->beginTransaction();
        try {
            $connection->insertMultiple('paypal_pos_coupon_delete', $data);
            $connection->commit();
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $connection->rollBack();
        }
    }
}
