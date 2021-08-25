<?php

namespace IWD\PaypalPos\Model\ResourceModel;

use Magento\Framework\Api\ExtensibleDataObjectConverter;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CategoryLoggerRepository implements \IWD\PaypalPos\Api\CategoryLoggerRepositoryInterface
{
    /**
     * @var  \IWD\PaypalPos\Api\Data\CategoryLoggerInterfaceFactory
     */
    private $categoryLoggerFactory;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\CategoryLogger
     */
    private $resource;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\CategoryLogger\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \IWD\PaypalPos\Api\Data\CategoryLoggerSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \IWD\PaypalPos\Model\CategoryLoggerRegistry
     */
    private $registry;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    public function __construct(
        \IWD\PaypalPos\Model\CategoryLoggerFactory $categoryLoggerFactory,
        \IWD\PaypalPos\Model\ResourceModel\CategoryLogger $resource,
        \IWD\PaypalPos\Model\ResourceModel\CategoryLogger\CollectionFactory $collectionFactory,
        \IWD\PaypalPos\Api\Data\CategoryLoggerSearchResultsInterfaceFactory $searchResultsFactory,
        \IWD\PaypalPos\Model\CategoryLoggerRegistry $registry,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->categoryLoggerFactory = $categoryLoggerFactory;
        $this->resource = $resource;
        $this->registry = $registry;
        $this->collectionFactory = $collectionFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\IWD\PaypalPos\Api\Data\CategoryLoggerInterface $categoryLogger)
    {
        $categoryLoggerData = $this->extensibleDataObjectConverter->toNestedArray(
            $categoryLogger,
            [],
            \IWD\PaypalPos\Api\Data\CategoryLoggerInterface::class
        );

        /** @var \IWD\PaypalPos\Model\CategoryLogger $categoryLoggerModel */
        $categoryLoggerModel = $this->categoryLoggerFactory->create(['data' => $categoryLoggerData]);
        $categoryLoggerModel->setDataChanges(true);
        if ($categoryLogger->getId()) {
            $categoryLoggerModel->setId($categoryLogger->getId());
        }
        $this->resource->save($categoryLoggerModel);
        $this->registry->push($categoryLoggerModel);

        return $this->getById($categoryLoggerModel->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $fromRegistry = $this->registry->retrieveById($id);
        if ($fromRegistry === null) {
            $categoryLogger = $this->categoryLoggerFactory->create();
            $this->resource->load($categoryLogger, $id);

            if (!$categoryLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such CategoryLogger'));
            }

            $this->registry->push($categoryLogger);
        }

        return $this->registry->retrieveById($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getByStoreId($value)
    {
        $fromRegistry = $this->registry->retrieveByStoreId($value);
        if ($fromRegistry === null) {
            $categoryLogger = $this->categoryLoggerFactory->create();
            $this->resource->load($categoryLogger, $value, 'store_id');

            if (!$categoryLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such CategoryLogger'));
            }

            $this->registry->push($categoryLogger);
        }

        return $this->registry->retrieveByStoreId($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getByCategoryId($value)
    {
        $fromRegistry = $this->registry->retrieveByCategoryId($value);
        if ($fromRegistry === null) {
            $categoryLogger = $this->categoryLoggerFactory->create();
            $this->resource->load($categoryLogger, $value, 'category_id');

            if (!$categoryLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such CategoryLogger'));
            }

            $this->registry->push($categoryLogger);
        }

        return $this->registry->retrieveByCategoryId($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getByCreatedAt($value)
    {
        $fromRegistry = $this->registry->retrieveByCreatedAt($value);
        if ($fromRegistry === null) {
            $categoryLogger = $this->categoryLoggerFactory->create();
            $this->resource->load($categoryLogger, $value, 'created_at');

            if (!$categoryLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such CategoryLogger'));
            }

            $this->registry->push($categoryLogger);
        }

        return $this->registry->retrieveByCreatedAt($value);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\IWD\PaypalPos\Api\Data\CategoryLoggerInterface $categoryLogger)
    {
        $categoryLoggerData = $this->extensibleDataObjectConverter->toNestedArray(
            $categoryLogger,
            [],
            \IWD\PaypalPos\Api\Data\CategoryLoggerInterface::class
        );

        /** @var \IWD\PaypalPos\Model\CategoryLogger $categoryLoggerModel */
        $categoryLoggerModel = $this->categoryLoggerFactory->create(['data' => $categoryLoggerData]);

        $categoryLoggerModel->setData($this->resource->getIdFieldName(), $categoryLogger->getId());

        $this->resource->delete($categoryLoggerModel);
        $this->registry->removeById($categoryLoggerModel->getId());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \IWD\PaypalPos\Api\Data\CategoryLoggerSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \IWD\PaypalPos\Model\ResourceModel\CategoryLogger\Collection $collection */
        $collection = $this->categoryLoggerFactory->create()->getCollection();
        $this->applySearchCriteriaToCollection($searchCriteria, $collection);

        $items = $this->convertCollectionToDataItemsArray($collection);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($items);

        return $searchResults;
    }

    private function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \IWD\PaypalPos\Model\ResourceModel\CategoryLogger\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\CategoryLogger\Collection $collection
    ) {
        $vendors = array_map(function (\IWD\PaypalPos\Model\CategoryLogger $item) {
            return $item->getDataModel();
        }, $collection->getItems());

        return $vendors;
    }

    private function applySearchCriteriaToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\CategoryLogger\Collection $collection
    ) {
        $this->applySearchCriteriaFiltersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaSortOrdersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaPagingToCollection($searchCriteria, $collection);
    }

    private function applySearchCriteriaFiltersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\CategoryLogger\Collection $collection
    ) {
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
    }

    private function applySearchCriteriaSortOrdersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\CategoryLogger\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\CategoryLogger\Collection $collection
    ) {
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
    }
}
