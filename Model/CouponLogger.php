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
class CouponLogger extends AbstractModel
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var \IWD\PaypalPos\Api\Data\CouponLoggerInterfaceFactory
     */
    private $couponLoggerDataFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        DataObjectHelper $dataObjectHelper,
        \IWD\PaypalPos\Api\Data\CouponLoggerInterfaceFactory $couponLoggerDataFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dataObjectHelper = $dataObjectHelper;
        $this->couponLoggerDataFactory = $couponLoggerDataFactory;
    }

    protected function _construct()
    {
        $this->_init(\IWD\PaypalPos\Model\ResourceModel\CouponLogger::class);
    }

    /**
     * Retrieve CouponLogger model
     *
     * @return \IWD\PaypalPos\Api\Data\CouponLoggerInterface
     */
    public function getDataModel()
    {
        $couponLoggerData = $this->getData();

        /** @var \IWD\PaypalPos\Api\Data\CouponLoggerInterface $couponLoggerDataObject */
        $couponLoggerDataObject = $this->couponLoggerDataFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $couponLoggerDataObject,
            $couponLoggerData,
            \IWD\PaypalPos\Api\Data\CouponLoggerInterface::class
        );
        $couponLoggerDataObject->setId($this->getId());

        return $couponLoggerDataObject;
    }
}
