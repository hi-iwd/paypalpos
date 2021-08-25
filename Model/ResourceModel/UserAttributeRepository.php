<?php

namespace IWD\PaypalPos\Model\ResourceModel;

use Magento\Framework\Api\ExtensibleDataObjectConverter;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UserAttributeRepository implements \IWD\PaypalPos\Api\UserAttributeRepositoryInterface
{
    /**
     * @var  \IWD\PaypalPos\Api\Data\UserAttributeInterfaceFactory
     */
    private $userAttributeFactory;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\UserAttribute
     */
    private $resource;

    /**
     * @var \IWD\PaypalPos\Model\ResourceModel\UserAttribute\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \IWD\PaypalPos\Api\Data\UserAttributeSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \IWD\PaypalPos\Model\UserAttributeRegistry
     */
    private $registry;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    public function __construct(
        \IWD\PaypalPos\Model\UserAttributeFactory $userAttributeFactory,
        \IWD\PaypalPos\Model\ResourceModel\UserAttribute $resource,
        \IWD\PaypalPos\Model\ResourceModel\UserAttribute\CollectionFactory $collectionFactory,
        \IWD\PaypalPos\Api\Data\UserAttributeSearchResultsInterfaceFactory $searchResultsFactory,
        \IWD\PaypalPos\Model\UserAttributeRegistry $registry,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->userAttributeFactory = $userAttributeFactory;
        $this->resource = $resource;
        $this->registry = $registry;
        $this->collectionFactory = $collectionFactory;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\IWD\PaypalPos\Api\Data\UserAttributeInterface $userAttribute)
    {
        $userAttributeData = $this->extensibleDataObjectConverter->toNestedArray(
            $userAttribute,
            [],
            \IWD\PaypalPos\Api\Data\UserAttributeInterface::class
        );

        /** @var \IWD\PaypalPos\Model\UserAttribute $userAttributeModel */
        $userAttributeModel = $this->userAttributeFactory->create(['data' => $userAttributeData]);
        $userAttributeModel->setDataChanges(true);
        if ($userAttribute->getId()) {
            $userAttributeModel->setId($userAttribute->getId());
        }
        $this->resource->save($userAttributeModel);
        $this->registry->push($userAttributeModel);

        return $this->getById($userAttributeModel->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        $fromRegistry = $this->registry->retrieveById($id);
        if ($fromRegistry === null) {
            $userAttribute = $this->userAttributeFactory->create();
            $this->resource->load($userAttribute, $id);

            if (!$userAttribute->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such UserAttribute'));
            }

            $this->registry->push($userAttribute);
        }

        return $this->registry->retrieveById($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getByOrderId($value)
    {
        $fromRegistry = $this->registry->retrieveByOrderId($value);
        if ($fromRegistry === null) {
            $userAttribute = $this->userAttributeFactory->create();
            $this->resource->load($userAttribute, $value, 'order_id');

            if (!$userAttribute->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(__('No such UserAttribute'));
            }

            $this->registry->push($userAttribute);
        }

        return $this->registry->retrieveByOrderId($value);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\IWD\PaypalPos\Api\Data\UserAttributeInterface $userAttribute)
    {
        $userAttributeData = $this->extensibleDataObjectConverter->toNestedArray(
            $userAttribute,
            [],
            \IWD\PaypalPos\Api\Data\UserAttributeInterface::class
        );

        /** @var \IWD\PaypalPos\Model\UserAttribute $userAttributeModel */
        $userAttributeModel = $this->userAttributeFactory->create(['data' => $userAttributeData]);

        $userAttributeModel->setData($this->resource->getIdFieldName(), $userAttribute->getId());

        $this->resource->delete($userAttributeModel);
        $this->registry->removeById($userAttributeModel->getId());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \IWD\PaypalPos\Api\Data\UserAttributeSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \IWD\PaypalPos\Model\ResourceModel\UserAttribute\Collection $collection */
        $collection = $this->userAttributeFactory->create()->getCollection();
        $this->applySearchCriteriaToCollection($searchCriteria, $collection);

        $items = $this->convertCollectionToDataItemsArray($collection);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($items);

        return $searchResults;
    }

    private function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \IWD\PaypalPos\Model\ResourceModel\UserAttribute\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\UserAttribute\Collection $collection
    ) {
        $vendors = array_map(function (\IWD\PaypalPos\Model\UserAttribute $item) {
            return $item->getDataModel();
        }, $collection->getItems());

        return $vendors;
    }

    private function applySearchCriteriaToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\UserAttribute\Collection $collection
    ) {
        $this->applySearchCriteriaFiltersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaSortOrdersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaPagingToCollection($searchCriteria, $collection);
    }

    private function applySearchCriteriaFiltersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\UserAttribute\Collection $collection
    ) {
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
    }

    private function applySearchCriteriaSortOrdersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \IWD\PaypalPos\Model\ResourceModel\UserAttribute\Collection $collection
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
        \IWD\PaypalPos\Model\ResourceModel\UserAttribute\Collection $collection
    ) {
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
    }
}
