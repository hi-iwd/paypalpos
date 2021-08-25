<?php

namespace IWD\PaypalPos\Model\ResourceModel;

use Magento\Framework\Api\ExtensibleDataObjectConverter;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UserLoggerRepository implements \IWD\PaypalPos\Api\UserLoggerRepositoryInterface
{
    /**
     * @var  \IWD\PaypalPos\Api\Data\UserLoggerInterfaceFactory
     */
    private $userLoggerFactory;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\UserLogger
     */
    private $resource;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\UserLogger\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \IWD\PaypalPos\Api\Data\UserLoggerSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \IWD\PaypalPos\Model\UserLoggerRegistry
     */
    private $registry;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    public function __construct(
        \IWD\PaypalPos\Model\UserLoggerFactory $userLoggerFactory,
        \IWD\PaypalPos\Model\ResourceModel\UserLogger $resource,
        \IWD\PaypalPos\Model\ResourceModel\UserLogger\CollectionFactory $collectionFactory,
        \IWD\PaypalPos\Api\Data\UserLoggerSearchResultsInterfaceFactory $searchResultsFactory,
        \IWD\PaypalPos\Model\UserLoggerRegistry $registry,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->userLoggerFactory = $userLoggerFactory;
        $this->resource = $resource;
        $this->registry = $registry;
        $this->collectionFactory = $collectionFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\IWD\PaypalPos\Api\Data\UserLoggerInterface $userLogger)
    {
        $userLoggerData = $this->extensibleDataObjectConverter->toNestedArray(
            $userLogger,
            [],
            \IWD\PaypalPos\Api\Data\UserLoggerInterface::class
        );

        /** @var \IWD\PaypalPos\Model\UserLogger $userLoggerModel */
        $userLoggerModel = $this->userLoggerFactory->create(['data' => $userLoggerData]);
        $userLoggerModel->setDataChanges(true);
        if ($userLogger->getId()) {
            $userLoggerModel->setId($userLogger->getId());
        }
        $this->resource->save($userLoggerModel);
        $this->registry->push($userLoggerModel);

        return $this->getById($userLoggerModel->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $fromRegistry = $this->registry->retrieveById($id);
        if ($fromRegistry === null) {
            $userLogger = $this->userLoggerFactory->create();
            $this->resource->load($userLogger, $id);

            if (!$userLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such UserLogger'));
            }

            $this->registry->push($userLogger);
        }

        return $this->registry->retrieveById($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getByUserId($value)
    {
        $fromRegistry = $this->registry->retrieveByUserId($value);
        if ($fromRegistry === null) {
            $userLogger = $this->userLoggerFactory->create();
            $this->resource->load($userLogger, $value, 'user_id');

            if (!$userLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such UserLogger'));
            }

            $this->registry->push($userLogger);
        }

        return $this->registry->retrieveByUserId($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getByEmail($value)
    {
        $fromRegistry = $this->registry->retrieveByEmail($value);
        if ($fromRegistry === null) {
            $userLogger = $this->userLoggerFactory->create();
            $this->resource->load($userLogger, $value, 'email');

            if (!$userLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such UserLogger'));
            }

            $this->registry->push($userLogger);
        }

        return $this->registry->retrieveByEmail($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getByCreatedAt($value)
    {
        $fromRegistry = $this->registry->retrieveByCreatedAt($value);
        if ($fromRegistry === null) {
            $userLogger = $this->userLoggerFactory->create();
            $this->resource->load($userLogger, $value, 'created_at');

            if (!$userLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such UserLogger'));
            }

            $this->registry->push($userLogger);
        }

        return $this->registry->retrieveByCreatedAt($value);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\IWD\PaypalPos\Api\Data\UserLoggerInterface $userLogger)
    {
        $userLoggerData = $this->extensibleDataObjectConverter->toNestedArray(
            $userLogger,
            [],
            \IWD\PaypalPos\Api\Data\UserLoggerInterface::class
        );

        /** @var \IWD\PaypalPos\Model\UserLogger $userLoggerModel */
        $userLoggerModel = $this->userLoggerFactory->create(['data' => $userLoggerData]);

        $userLoggerModel->setData($this->resource->getIdFieldName(), $userLogger->getId());

        $this->resource->delete($userLoggerModel);
        $this->registry->removeById($userLoggerModel->getId());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \IWD\PaypalPos\Api\Data\UserLoggerSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \IWD\PaypalPos\Model\ResourceModel\UserLogger\Collection $collection */
        $collection = $this->userLoggerFactory->create()->getCollection();
        $this->applySearchCriteriaToCollection($searchCriteria, $collection);

        $items = $this->convertCollectionToDataItemsArray($collection);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($items);

        return $searchResults;
    }

    private function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \IWD\PaypalPos\Model\ResourceModel\UserLogger\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\UserLogger\Collection $collection
    ) {
        $vendors = array_map(function (\IWD\PaypalPos\Model\UserLogger $item) {
            return $item->getDataModel();
        }, $collection->getItems());

        return $vendors;
    }

    private function applySearchCriteriaToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\UserLogger\Collection $collection
    ) {
        $this->applySearchCriteriaFiltersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaSortOrdersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaPagingToCollection($searchCriteria, $collection);
    }

    private function applySearchCriteriaFiltersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\UserLogger\Collection $collection
    ) {
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
    }

    private function applySearchCriteriaSortOrdersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\UserLogger\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\UserLogger\Collection $collection
    ) {
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
    }
}
