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
class CustomerLogger extends AbstractModel
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var \IWD\PaypalPos\Api\Data\CustomerLoggerInterfaceFactory
     */
    private $customerLoggerDataFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        DataObjectHelper $dataObjectHelper,
        \IWD\PaypalPos\Api\Data\CustomerLoggerInterfaceFactory $customerLoggerDataFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dataObjectHelper = $dataObjectHelper;
        $this->customerLoggerDataFactory = $customerLoggerDataFactory;
    }

    protected function _construct()
    {
        $this->_init(\IWD\PaypalPos\Model\ResourceModel\CustomerLogger::class);
    }

    /**
     * Retrieve CustomerLogger model
     *
     * @return \IWD\PaypalPos\Api\Data\CustomerLoggerInterface
     */
    public function getDataModel()
    {
        $customerLoggerData = $this->getData();

        /** @var \IWD\PaypalPos\Api\Data\CustomerLoggerInterface $customerLoggerDataObject */
        $customerLoggerDataObject = $this->customerLoggerDataFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $customerLoggerDataObject,
            $customerLoggerData,
            \IWD\PaypalPos\Api\Data\CustomerLoggerInterface::class
        );
        $customerLoggerDataObject->setId($this->getId());

        return $customerLoggerDataObject;
    }
}
