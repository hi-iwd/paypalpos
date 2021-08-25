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
class AdminUser extends AbstractModel
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var \IWD\PaypalPos\Api\Data\AdminUserInterfaceFactory
     */
    private $adminUserDataFactory;

    /**
     * AdminUser constructor.
     * @param Context $context
     * @param Registry $registry
     * @param DataObjectHelper $dataObjectHelper
     * @param \IWD\PaypalPos\Api\Data\AdminUserInterfaceFactory $adminUserDataFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DataObjectHelper $dataObjectHelper,
        \IWD\PaypalPos\Api\Data\AdminUserInterfaceFactory $adminUserDataFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->dataObjectHelper = $dataObjectHelper;
        $this->adminUserDataFactory = $adminUserDataFactory;
    }

    protected function _construct()
    {
        $this->_init(\IWD\PaypalPos\Model\ResourceModel\AdminUser::class);
    }

    /**
     * Retrieve AdminUser model
     *
     * @return \IWD\PaypalPos\Api\Data\AdminUserInterface
     */
    public function getDataModel()
    {
        $adminUserData = $this->getData();

        /** @var \IWD\PaypalPos\Api\Data\AdminUserInterface $adminUserDataObject */
        $adminUserDataObject = $this->adminUserDataFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $adminUserDataObject,
            $adminUserData,
            \IWD\PaypalPos\Api\Data\AdminUserInterface::class
        );
        $adminUserDataObject->setId($this->getId());

        return $adminUserDataObject;
    }
}
