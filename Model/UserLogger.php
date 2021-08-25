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
class UserLogger extends AbstractModel
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var \IWD\PaypalPos\Api\Data\UserLoggerInterfaceFactory
     */
    private $userLoggerDataFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        DataObjectHelper $dataObjectHelper,
        \IWD\PaypalPos\Api\Data\UserLoggerInterfaceFactory $userLoggerDataFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dataObjectHelper = $dataObjectHelper;
        $this->userLoggerDataFactory = $userLoggerDataFactory;
    }

    protected function _construct()
    {
        $this->_init(\IWD\PaypalPos\Model\ResourceModel\UserLogger::class);
    }

    /**
     * Retrieve UserLogger model
     *
     * @return \IWD\PaypalPos\Api\Data\UserLoggerInterface
     */
    public function getDataModel()
    {
        $userLoggerData = $this->getData();

        /** @var \IWD\PaypalPos\Api\Data\UserLoggerInterface $userLoggerDataObject */
        $userLoggerDataObject = $this->userLoggerDataFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $userLoggerDataObject,
            $userLoggerData,
            \IWD\PaypalPos\Api\Data\UserLoggerInterface::class
        );
        $userLoggerDataObject->setId($this->getId());

        return $userLoggerDataObject;
    }
}
