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
class UserAttribute extends AbstractModel
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var \IWD\PaypalPos\Api\Data\UserAttributeInterfaceFactory
     */
    private $userAttributeDataFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        DataObjectHelper $dataObjectHelper,
        \IWD\PaypalPos\Api\Data\UserAttributeInterfaceFactory $userAttributeDataFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dataObjectHelper = $dataObjectHelper;
        $this->userAttributeDataFactory = $userAttributeDataFactory;
    }

    protected function _construct()
    {
        $this->_init(\IWD\PaypalPos\Model\ResourceModel\UserAttribute::class);
    }

    /**
     * Retrieve UserAttribute model
     *
     * @return \IWD\PaypalPos\Api\Data\UserAttributeInterface
     */
    public function getDataModel()
    {
        $userAttributeData = $this->getData();

        /** @var \IWD\PaypalPos\Api\Data\UserAttributeInterface $userAttributeDataObject */
        $userAttributeDataObject = $this->userAttributeDataFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $userAttributeDataObject,
            $userAttributeData,
            \IWD\PaypalPos\Api\Data\UserAttributeInterface::class
        );
        $userAttributeDataObject->setId($this->getId());

        return $userAttributeDataObject;
    }
}
