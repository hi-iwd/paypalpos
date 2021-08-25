<?php

namespace IWD\PaypalPos\Model\ResourceModel;

use Magento\Framework\Api\ExtensibleDataObjectConverter;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CouponLoggerRepository implements \IWD\PaypalPos\Api\CouponLoggerRepositoryInterface
{
    /**
     * @var  \IWD\PaypalPos\Api\Data\CouponLoggerInterfaceFactory
     */
    private $couponLoggerFactory;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\CouponLogger
     */
    private $resource;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\CouponLogger\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \IWD\PaypalPos\Api\Data\CouponLoggerSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \IWD\PaypalPos\Model\CouponLoggerRegistry
     */
    private $registry;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    public function __construct(
        \IWD\PaypalPos\Model\CouponLoggerFactory $couponLoggerFactory,
        \IWD\PaypalPos\Model\ResourceModel\CouponLogger $resource,
        \IWD\PaypalPos\Model\ResourceModel\CouponLogger\CollectionFactory $collectionFactory,
        \IWD\PaypalPos\Api\Data\CouponLoggerSearchResultsInterfaceFactory $searchResultsFactory,
        \IWD\PaypalPos\Model\CouponLoggerRegistry $registry,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->couponLoggerFactory = $couponLoggerFactory;
        $this->resource = $resource;
        $this->registry = $registry;
        $this->collectionFactory = $collectionFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\IWD\PaypalPos\Api\Data\CouponLoggerInterface $couponLogger)
    {
        $couponLoggerData = $this->extensibleDataObjectConverter->toNestedArray(
            $couponLogger,
            [],
            \IWD\PaypalPos\Api\Data\CouponLoggerInterface::class
        );

        /** @var \IWD\PaypalPos\Model\CouponLogger $couponLoggerModel */
        $couponLoggerModel = $this->couponLoggerFactory->create(['data' => $couponLoggerData]);
        $couponLoggerModel->setDataChanges(true);
        if ($couponLogger->getId()) {
            $couponLoggerModel->setId($couponLogger->getId());
        }
        $this->resource->save($couponLoggerModel);
        $this->registry->push($couponLoggerModel);

        return $this->getById($couponLoggerModel->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $fromRegistry = $this->registry->retrieveById($id);
        if ($fromRegistry === null) {
            $couponLogger = $this->couponLoggerFactory->create();
            $this->resource->load($couponLogger, $id);

            if (!$couponLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such CouponLogger'));
            }

            $this->registry->push($couponLogger);
        }

        return $this->registry->retrieveById($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getByRuleId($value)
    {
        $fromRegistry = $this->registry->retrieveByRuleId($value);
        if ($fromRegistry === null) {
            $couponLogger = $this->couponLoggerFactory->create();
            $this->resource->load($couponLogger, $value, 'rule_id');

            if (!$couponLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such CouponLogger'));
            }

            $this->registry->push($couponLogger);
        }

        return $this->registry->retrieveByRuleId($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getByIsUsed($value)
    {
        $fromRegistry = $this->registry->retrieveByIsUsed($value);
        if ($fromRegistry === null) {
            $couponLogger = $this->couponLoggerFactory->create();
            $this->resource->load($couponLogger, $value, 'is_used');

            if (!$couponLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such CouponLogger'));
            }

            $this->registry->push($couponLogger);
        }

        return $this->registry->retrieveByIsUsed($value);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\IWD\PaypalPos\Api\Data\CouponLoggerInterface $couponLogger)
    {
        $couponLoggerData = $this->extensibleDataObjectConverter->toNestedArray(
            $couponLogger,
            [],
            \IWD\PaypalPos\Api\Data\CouponLoggerInterface::class
        );

        /** @var \IWD\PaypalPos\Model\CouponLogger $couponLoggerModel */
        $couponLoggerModel = $this->couponLoggerFactory->create(['data' => $couponLoggerData]);

        $couponLoggerModel->setData($this->resource->getIdFieldName(), $couponLogger->getId());

        $this->resource->delete($couponLoggerModel);
        $this->registry->removeById($couponLoggerModel->getId());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \IWD\PaypalPos\Api\Data\CouponLoggerSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \IWD\PaypalPos\Model\ResourceModel\CouponLogger\Collection $collection */
        $collection = $this->couponLoggerFactory->create()->getCollection();
        $this->applySearchCriteriaToCollection($searchCriteria, $collection);

        $items = $this->convertCollectionToDataItemsArray($collection);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($items);

        return $searchResults;
    }

    private function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \IWD\PaypalPos\Model\ResourceModel\CouponLogger\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\CouponLogger\Collection $collection
    ) {
        $vendors = array_map(function (\IWD\PaypalPos\Model\CouponLogger $item) {
            return $item->getDataModel();
        }, $collection->getItems());

        return $vendors;
    }

    private function applySearchCriteriaToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\CouponLogger\Collection $collection
    ) {
        $this->applySearchCriteriaFiltersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaSortOrdersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaPagingToCollection($searchCriteria, $collection);
    }

    private function applySearchCriteriaFiltersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\CouponLogger\Collection $collection
    ) {
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
    }

    private function applySearchCriteriaSortOrdersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\CouponLogger\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\CouponLogger\Collection $collection
    ) {
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
    }
}
