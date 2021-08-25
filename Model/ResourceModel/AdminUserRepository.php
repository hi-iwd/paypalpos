<?php

namespace IWD\PaypalPos\Model\ResourceModel;

use Magento\Framework\Api\ExtensibleDataObjectConverter;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AdminUserRepository implements \IWD\PaypalPos\Api\AdminUserRepositoryInterface
{
    /**
     * @var  \IWD\PaypalPos\Api\Data\AdminUserInterfaceFactory
     */
    private $adminUserFactory;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\AdminUser
     */
    private $resource;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\AdminUser\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \IWD\PaypalPos\Api\Data\AdminUserSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \IWD\PaypalPos\Model\AdminUserRegistry
     */
    private $registry;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    public function __construct(
        \IWD\PaypalPos\Model\AdminUserFactory $adminUserFactory,
        \IWD\PaypalPos\Model\ResourceModel\AdminUser $resource,
        \IWD\PaypalPos\Model\ResourceModel\AdminUser\CollectionFactory $collectionFactory,
        \IWD\PaypalPos\Api\Data\AdminUserSearchResultsInterfaceFactory $searchResultsFactory,
        \IWD\PaypalPos\Model\AdminUserRegistry $registry,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->adminUserFactory = $adminUserFactory;
        $this->resource = $resource;
        $this->registry = $registry;
        $this->collectionFactory = $collectionFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\IWD\PaypalPos\Api\Data\AdminUserInterface $adminUser)
    {
        $adminUserData = $this->extensibleDataObjectConverter->toNestedArray(
            $adminUser,
            [],
            \IWD\PaypalPos\Api\Data\AdminUserInterface::class
        );

        /** @var \IWD\PaypalPos\Model\AdminUser $adminUserModel */
        $adminUserModel = $this->adminUserFactory->create(['data' => $adminUserData]);
        $adminUserModel->setDataChanges(true);
        if ($adminUser->getId()) {
            $adminUserModel->setId($adminUser->getId());
        }
        $this->resource->save($adminUserModel);
        $this->registry->push($adminUserModel);

        return $this->getById($adminUserModel->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $fromRegistry = $this->registry->retrieveById($id);
        if ($fromRegistry === null) {
            $adminUser = $this->adminUserFactory->create();
            $this->resource->load($adminUser, $id);

            if (!$adminUser->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such AdminUser'));
            }

            $this->registry->push($adminUser);
        }

        return $this->registry->retrieveById($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getByUsername($value)
    {
        $fromRegistry = $this->registry->retrieveByUsername($value);
        if ($fromRegistry === null) {
            $adminUser = $this->adminUserFactory->create();
            $this->resource->load($adminUser, $value, 'username');

            if (!$adminUser->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such AdminUser'));
            }

            $this->registry->push($adminUser);
        }

        return $this->registry->retrieveByUsername($value);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\IWD\PaypalPos\Api\Data\AdminUserInterface $adminUser)
    {
        $adminUserData = $this->extensibleDataObjectConverter->toNestedArray(
            $adminUser,
            [],
            \IWD\PaypalPos\Api\Data\AdminUserInterface::class
        );

        /** @var \IWD\PaypalPos\Model\AdminUser $adminUserModel */
        $adminUserModel = $this->adminUserFactory->create(['data' => $adminUserData]);

        $adminUserModel->setData($this->resource->getIdFieldName(), $adminUser->getId());

        $this->resource->delete($adminUserModel);
        $this->registry->removeById($adminUserModel->getId());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \IWD\PaypalPos\Api\Data\AdminUserSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \IWD\PaypalPos\Model\ResourceModel\AdminUser\Collection $collection */
        $collection = $this->adminUserFactory->create()->getCollection();
        $this->applySearchCriteriaToCollection($searchCriteria, $collection);

        $items = $this->convertCollectionToDataItemsArray($collection);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($items);

        return $searchResults;
    }

    private function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \IWD\PaypalPos\Model\ResourceModel\AdminUser\Collection $collection
    ) {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ?: 'eq';
            $fields[] = $filter->getField();

            $conditions[] = [$condition => $filter->getValue()];
        }

        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    private function convertCollectionToDataItemsArray(
        \IWD\PaypalPos\Model\ResourceModel\AdminUser\Collection $collection
    ) {
        $vendors = array_map(function (\IWD\PaypalPos\Model\AdminUser $item) {
            return $item->getDataModel();
        }, $collection->getItems());

        return $vendors;
    }

    private function applySearchCriteriaToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\AdminUser\Collection $collection
    ) {
        $this->applySearchCriteriaFiltersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaSortOrdersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaPagingToCollection($searchCriteria, $collection);
    }

    private function applySearchCriteriaFiltersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\AdminUser\Collection $collection
    ) {
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
    }

    private function applySearchCriteriaSortOrdersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\AdminUser\Collection $collection
    ) {
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $isAscending = $sortOrder->getDirection() == \Magento\Framework\Api\SortOrder::SORT_ASC;
                $collection->addOrder($sortOrder->getField(), $isAscending ? 'ASC' : 'DESC');
            }
        }
    }

    private function applySearchCriteriaPagingToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\AdminUser\Collection $collection
    ) {
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
    }
}
