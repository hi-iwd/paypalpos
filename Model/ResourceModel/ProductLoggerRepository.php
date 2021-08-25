<?php

namespace IWD\PaypalPos\Model\ResourceModel;

use Magento\Framework\Api\ExtensibleDataObjectConverter;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductLoggerRepository implements \IWD\PaypalPos\Api\ProductLoggerRepositoryInterface
{
    /**
     * @var  \IWD\PaypalPos\Api\Data\ProductLoggerInterfaceFactory
     */
    private $productLoggerFactory;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\ProductLogger
     */
    private $resource;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\ProductLogger\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \IWD\PaypalPos\Api\Data\ProductLoggerSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \IWD\PaypalPos\Model\ProductLoggerRegistry
     */
    private $registry;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    public function __construct(
        \IWD\PaypalPos\Model\ProductLoggerFactory $productLoggerFactory,
        \IWD\PaypalPos\Model\ResourceModel\ProductLogger $resource,
        \IWD\PaypalPos\Model\ResourceModel\ProductLogger\CollectionFactory $collectionFactory,
        \IWD\PaypalPos\Api\Data\ProductLoggerSearchResultsInterfaceFactory $searchResultsFactory,
        \IWD\PaypalPos\Model\ProductLoggerRegistry $registry,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->productLoggerFactory = $productLoggerFactory;
        $this->resource = $resource;
        $this->registry = $registry;
        $this->collectionFactory = $collectionFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\IWD\PaypalPos\Api\Data\ProductLoggerInterface $productLogger)
    {
        $productLoggerData = $this->extensibleDataObjectConverter->toNestedArray(
            $productLogger,
            [],
            \IWD\PaypalPos\Api\Data\ProductLoggerInterface::class
        );

        /** @var \IWD\PaypalPos\Model\ProductLogger $productLoggerModel */
        $productLoggerModel = $this->productLoggerFactory->create(['data' => $productLoggerData]);
        $productLoggerModel->setDataChanges(true);
        if ($productLogger->getId()) {
            $productLoggerModel->setId($productLogger->getId());
        }
        $this->resource->save($productLoggerModel);
        $this->registry->push($productLoggerModel);

        return $this->getById($productLoggerModel->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $fromRegistry = $this->registry->retrieveById($id);
        if ($fromRegistry === null) {
            $productLogger = $this->productLoggerFactory->create();
            $this->resource->load($productLogger, $id);

            if (!$productLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such ProductLogger'));
            }

            $this->registry->push($productLogger);
        }

        return $this->registry->retrieveById($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getByProductId($value)
    {
        $fromRegistry = $this->registry->retrieveByProductId($value);
        if ($fromRegistry === null) {
            $productLogger = $this->productLoggerFactory->create();
            $this->resource->load($productLogger, $value, 'product_id');

            if (!$productLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such ProductLogger'));
            }

            $this->registry->push($productLogger);
        }

        return $this->registry->retrieveByProductId($value);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\IWD\PaypalPos\Api\Data\ProductLoggerInterface $productLogger)
    {
        $productLoggerData = $this->extensibleDataObjectConverter->toNestedArray(
            $productLogger,
            [],
            \IWD\PaypalPos\Api\Data\ProductLoggerInterface::class
        );

        /** @var \IWD\PaypalPos\Model\ProductLogger $productLoggerModel */
        $productLoggerModel = $this->productLoggerFactory->create(['data' => $productLoggerData]);

        $productLoggerModel->setData($this->resource->getIdFieldName(), $productLogger->getId());

        $this->resource->delete($productLoggerModel);
        $this->registry->removeById($productLoggerModel->getId());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \IWD\PaypalPos\Api\Data\ProductLoggerSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \IWD\PaypalPos\Model\ResourceModel\ProductLogger\Collection $collection */
        $collection = $this->productLoggerFactory->create()->getCollection();
        $this->applySearchCriteriaToCollection($searchCriteria, $collection);

        $items = $this->convertCollectionToDataItemsArray($collection);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($items);

        return $searchResults;
    }

    private function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \IWD\PaypalPos\Model\ResourceModel\ProductLogger\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\ProductLogger\Collection $collection
    ) {
        $vendors = array_map(function (\IWD\PaypalPos\Model\ProductLogger $item) {
            return $item->getDataModel();
        }, $collection->getItems());

        return $vendors;
    }

    private function applySearchCriteriaToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\ProductLogger\Collection $collection
    ) {
        $this->applySearchCriteriaFiltersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaSortOrdersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaPagingToCollection($searchCriteria, $collection);
    }

    private function applySearchCriteriaFiltersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\ProductLogger\Collection $collection
    ) {
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
    }

    private function applySearchCriteriaSortOrdersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\ProductLogger\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\ProductLogger\Collection $collection
    ) {
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
    }
}
