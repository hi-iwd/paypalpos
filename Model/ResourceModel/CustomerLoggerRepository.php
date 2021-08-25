<?php

namespace IWD\PaypalPos\Model\ResourceModel;

use Magento\Framework\Api\ExtensibleDataObjectConverter;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomerLoggerRepository implements \IWD\PaypalPos\Api\CustomerLoggerRepositoryInterface
{
    /**
     * @var  \IWD\PaypalPos\Api\Data\CustomerLoggerInterfaceFactory
     */
    private $customerLoggerFactory;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\CustomerLogger
     */
    private $resource;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\CustomerLogger\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \IWD\PaypalPos\Api\Data\CustomerLoggerSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \IWD\PaypalPos\Model\CustomerLoggerRegistry
     */
    private $registry;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    public function __construct(
        \IWD\PaypalPos\Model\CustomerLoggerFactory $customerLoggerFactory,
        \IWD\PaypalPos\Model\ResourceModel\CustomerLogger $resource,
        \IWD\PaypalPos\Model\ResourceModel\CustomerLogger\CollectionFactory $collectionFactory,
        \IWD\PaypalPos\Api\Data\CustomerLoggerSearchResultsInterfaceFactory $searchResultsFactory,
        \IWD\PaypalPos\Model\CustomerLoggerRegistry $registry,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->customerLoggerFactory = $customerLoggerFactory;
        $this->resource = $resource;
        $this->registry = $registry;
        $this->collectionFactory = $collectionFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\IWD\PaypalPos\Api\Data\CustomerLoggerInterface $customerLogger)
    {
        $customerLoggerData = $this->extensibleDataObjectConverter->toNestedArray(
            $customerLogger,
            [],
            \IWD\PaypalPos\Api\Data\CustomerLoggerInterface::class
        );

        /** @var \IWD\PaypalPos\Model\CustomerLogger $customerLoggerModel */
        $customerLoggerModel = $this->customerLoggerFactory->create(['data' => $customerLoggerData]);
        $customerLoggerModel->setDataChanges(true);
        if ($customerLogger->getId()) {
            $customerLoggerModel->setId($customerLogger->getId());
        }
        $this->resource->save($customerLoggerModel);
        $this->registry->push($customerLoggerModel);

        return $this->getById($customerLoggerModel->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $fromRegistry = $this->registry->retrieveById($id);
        if ($fromRegistry === null) {
            $customerLogger = $this->customerLoggerFactory->create();
            $this->resource->load($customerLogger, $id);

            if (!$customerLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such CustomerLogger'));
            }

            $this->registry->push($customerLogger);
        }

        return $this->registry->retrieveById($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getByEmail($value)
    {
        $fromRegistry = $this->registry->retrieveByEmail($value);
        if ($fromRegistry === null) {
            $customerLogger = $this->customerLoggerFactory->create();
            $this->resource->load($customerLogger, $value, 'email');

            if (!$customerLogger->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such CustomerLogger'));
            }

            $this->registry->push($customerLogger);
        }

        return $this->registry->retrieveByEmail($value);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\IWD\PaypalPos\Api\Data\CustomerLoggerInterface $customerLogger)
    {
        $customerLoggerData = $this->extensibleDataObjectConverter->toNestedArray(
            $customerLogger,
            [],
            \IWD\PaypalPos\Api\Data\CustomerLoggerInterface::class
        );

        /** @var \IWD\PaypalPos\Model\CustomerLogger $customerLoggerModel */
        $customerLoggerModel = $this->customerLoggerFactory->create(['data' => $customerLoggerData]);

        $customerLoggerModel->setData($this->resource->getIdFieldName(), $customerLogger->getId());

        $this->resource->delete($customerLoggerModel);
        $this->registry->removeById($customerLoggerModel->getId());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \IWD\PaypalPos\Api\Data\CustomerLoggerSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \IWD\PaypalPos\Model\ResourceModel\CustomerLogger\Collection $collection */
        $collection = $this->customerLoggerFactory->create()->getCollection();
        $this->applySearchCriteriaToCollection($searchCriteria, $collection);

        $items = $this->convertCollectionToDataItemsArray($collection);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($items);

        return $searchResults;
    }

    private function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \IWD\PaypalPos\Model\ResourceModel\CustomerLogger\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\CustomerLogger\Collection $collection
    ) {
        $vendors = array_map(function (\IWD\PaypalPos\Model\CustomerLogger $item) {
            return $item->getDataModel();
        }, $collection->getItems());

        return $vendors;
    }

    private function applySearchCriteriaToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\CustomerLogger\Collection $collection
    ) {
        $this->applySearchCriteriaFiltersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaSortOrdersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaPagingToCollection($searchCriteria, $collection);
    }

    private function applySearchCriteriaFiltersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\CustomerLogger\Collection $collection
    ) {
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
    }

    private function applySearchCriteriaSortOrdersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\CustomerLogger\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\CustomerLogger\Collection $collection
    ) {
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
    }
}
