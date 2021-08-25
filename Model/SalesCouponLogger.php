<?php

namespace IWD\PaypalPos\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class SalesCouponLogger extends AbstractModel
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterfaceFactory
     */
    private $salesCouponLoggerDataFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        DataObjectHelper $dataObjectHelper,
        \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterfaceFactory $salesCouponLoggerDataFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dataObjectHelper = $dataObjectHelper;
        $this->salesCouponLoggerDataFactory = $salesCouponLoggerDataFactory;
    }

    protected function _construct()
    {
        $this->_init(\IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger::class);
    }

    /**
     * Retrieve SalesCouponLogger model
     *
     * @return \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface
     */
    public function getDataModel()
    {
        $salesCouponLoggerData = $this->getData();

        /** @var \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface $salesCouponLoggerDataObject */
        $salesCouponLoggerDataObject = $this->salesCouponLoggerDataFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $salesCouponLoggerDataObject,
            $salesCouponLoggerData,
            \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface::class
        );
        $salesCouponLoggerDataObject->setId($this->getId());

        return $salesCouponLoggerDataObject;
    }
}
