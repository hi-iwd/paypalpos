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
class ProductLogger extends AbstractModel
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var \IWD\PaypalPos\Api\Data\ProductLoggerInterfaceFactory
     */
    private $productLoggerDataFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        DataObjectHelper $dataObjectHelper,
        \IWD\PaypalPos\Api\Data\ProductLoggerInterfaceFactory $productLoggerDataFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dataObjectHelper = $dataObjectHelper;
        $this->productLoggerDataFactory = $productLoggerDataFactory;
    }

    protected function _construct()
    {
        $this->_init(\IWD\PaypalPos\Model\ResourceModel\ProductLogger::class);
    }

    /**
     * Retrieve ProductLogger model
     *
     * @return \IWD\PaypalPos\Api\Data\ProductLoggerInterface
     */
    public function getDataModel()
    {
        $productLoggerData = $this->getData();

        /** @var \IWD\PaypalPos\Api\Data\ProductLoggerInterface $productLoggerDataObject */
        $productLoggerDataObject = $this->productLoggerDataFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $productLoggerDataObject,
            $productLoggerData,
            \IWD\PaypalPos\Api\Data\ProductLoggerInterface::class
        );
        $productLoggerDataObject->setId($this->getId());

        return $productLoggerDataObject;
    }
}
