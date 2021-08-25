<?php

namespace IWD\PaypalPos\Model\ResourceModel;

use Magento\Framework\Api\ExtensibleDataObjectConverter;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SalesCouponLoggerRepository implements \IWD\PaypalPos\Api\SalesCouponLoggerRepositoryInterface
{
    /**
     * @var  \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterfaceFactory
     */
    private $salesCouponLoggerFactory;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger
     */
    private $resource;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \IWD\PaypalPos\Api\Data\SalesCouponLoggerSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \IWD\PaypalPos\Model\SalesCouponLoggerRegistry
     */
    private $registry;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    public function __construct(
        \IWD\PaypalPos\Model\SalesCouponLoggerFactory $salesCouponLoggerFactory,
        \IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger $resource,
        \IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger\CollectionFactory $collectionFactory,
        \IWD\PaypalPos\Api\Data\SalesCouponLoggerSearchResultsInterfaceFactory $searchResultsFactory,
        \IWD\PaypalPos\Model\SalesCouponLoggerRegistry $registry,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->salesCouponLoggerFactory = $salesCouponLoggerFactory;
        $this->resource = $resource;
        $this->registry = $registry;
        $this->collectionFactory = $collectionFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface $salesCouponLogger)
    {
        $salesCouponLoggerData = $this->extensibleDataObjectConverter->toNestedArray(
            $salesCouponLogger,
            [],
            \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface::class
        );

        /** @var \IWD\PaypalPos\Model\SalesCouponLogger $salesCouponLoggerModel */
        $salesCouponLoggerModel = $this->salesCouponLoggerFactory->create(['data' => $salesCouponLoggerData]);
        $salesCouponLoggerModel->setDataChanges(true);
        if ($salesCouponLogger->getId()) {
            $salesCouponLoggerModel->setId($salesCouponLogger->getId());
        }
        $this->resource->save($salesCouponLoggerModel);
        $this->registry->push($salesCouponLoggerModel);

        return $this->getById($salesCouponLoggerModel->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $fromRegistry = $this->registry->retrieveById($id);
        if ($fromRegistry === null) {
            $salesCouponLogger = $this->salesCouponLoggerFactory->create();
            $this->resource->load($salesCouponLogger, $id);

            if (!$salesCouponLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such SalesCouponLogger'));
            }

            $this->registry->push($salesCouponLogger);
        }

        return $this->registry->retrieveById($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getByCouponId($value)
    {
        $fromRegistry = $this->registry->retrieveByCouponId($value);
        if ($fromRegistry === null) {
            $salesCouponLogger = $this->salesCouponLoggerFactory->create();
            $this->resource->load($salesCouponLogger, $value, 'coupon_id');

            if (!$salesCouponLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such SalesCouponLogger'));
            }

            $this->registry->push($salesCouponLogger);
        }

        return $this->registry->retrieveByCouponId($value);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface $salesCouponLogger)
    {
        $salesCouponLoggerData = $this->extensibleDataObjectConverter->toNestedArray(
            $salesCouponLogger,
            [],
            \IWD\PaypalPos\Api\Data\SalesCouponLoggerInterface::class
        );

        /** @var \IWD\PaypalPos\Model\SalesCouponLogger $salesCouponLoggerModel */
        $salesCouponLoggerModel = $this->salesCouponLoggerFactory->create(['data' => $salesCouponLoggerData]);

        $salesCouponLoggerModel->setData($this->resource->getIdFieldName(), $salesCouponLogger->getId());

        $this->resource->delete($salesCouponLoggerModel);
        $this->registry->removeById($salesCouponLoggerModel->getId());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \IWD\PaypalPos\Api\Data\SalesCouponLoggerSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger\Collection $collection */
        $collection = $this->salesCouponLoggerFactory->create()->getCollection();
        $this->applySearchCriteriaToCollection($searchCriteria, $collection);

        $items = $this->convertCollectionToDataItemsArray($collection);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($items);

        return $searchResults;
    }

    private function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger\Collection $collection
    ) {
        $vendors = array_map(function (\IWD\PaypalPos\Model\SalesCouponLogger $item) {
            return $item->getDataModel();
        }, $collection->getItems());

        return $vendors;
    }

    private function applySearchCriteriaToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger\Collection $collection
    ) {
        $this->applySearchCriteriaFiltersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaSortOrdersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaPagingToCollection($searchCriteria, $collection);
    }

    private function applySearchCriteriaFiltersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger\Collection $collection
    ) {
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
    }

    private function applySearchCriteriaSortOrdersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\SalesCouponLogger\Collection $collection
    ) {
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
    }
}
