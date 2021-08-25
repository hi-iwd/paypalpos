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
class CategoryLogger extends AbstractModel
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var \IWD\PaypalPos\Api\Data\CategoryLoggerInterfaceFactory
     */
    private $categoryLoggerDataFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        DataObjectHelper $dataObjectHelper,
        \IWD\PaypalPos\Api\Data\CategoryLoggerInterfaceFactory $categoryLoggerDataFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dataObjectHelper = $dataObjectHelper;
        $this->categoryLoggerDataFactory = $categoryLoggerDataFactory;
    }

    protected function _construct()
    {
        $this->_init(\IWD\PaypalPos\Model\ResourceModel\CategoryLogger::class);
    }

    /**
     * Retrieve CategoryLogger model
     *
     * @return \IWD\PaypalPos\Api\Data\CategoryLoggerInterface
     */
    public function getDataModel()
    {
        $categoryLoggerData = $this->getData();

        /** @var \IWD\PaypalPos\Api\Data\CategoryLoggerInterface $categoryLoggerDataObject */
        $categoryLoggerDataObject = $this->categoryLoggerDataFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $categoryLoggerDataObject,
            $categoryLoggerData,
            \IWD\PaypalPos\Api\Data\CategoryLoggerInterface::class
        );
        $categoryLoggerDataObject->setId($this->getId());

        return $categoryLoggerDataObject;
    }
}
