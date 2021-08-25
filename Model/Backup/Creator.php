<?php

namespace IWD\PaypalPos\Model\Backup;

use Magento\Backup\Model\ResourceModel\Table\GetListTables;
use IWD\PaypalPos\Model\ResourceModel\Backup\Structure;
use IWD\PaypalPos\Model\Backup\File;
use Magento\Backup\Model\ResourceModel\Db;
use Magento\Framework\App\ResourceConnection;
use IWD\PaypalPos\Model\Backup\Zip;
use Magento\InventoryIndexer\Model\StockIndexTableNameResolverInterface;
use Magento\InventoryApi\Api\StockRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\InventoryApi\Api\Data\StockInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Indexer\Category\Product\TableMaintainer;

class Creator
{
    const BUFFER_LENGTH = 51000;

    private $tablesList = [
        'store_website',
        'sales_order_item',
        'admin_user',
        'store_group',
        'salesrule_website',
        'catalog_product_website',
        'catalog_category_product',
        'customer_entity',
        'customer_group',
        'sales_order',
        'sales_order_grid',
        'sales_order_status',
        'sales_order_payment',
        'sales_order_status_history',
        'directory_country_region',
        'catalog_category_entity',
        'catalog_category_entity_varchar',
        'eav_attribute',
        'catalog_product_entity',
        'catalog_product_index_price',
        'catalog_product_relation',
        'customer_address_entity',
        'catalog_eav_attribute',
        'catalog_product_entity_varchar',
        'eav_entity_type',
        'catalog_product_super_attribute',
        'catalog_product_super_attribute_label',
        'catalog_product_super_link',
        'catalog_product_entity_int',
        'eav_attribute_option',
        'eav_attribute_option_value',
        'catalog_product_bundle_option',
        'catalog_product_bundle_option_value',
        'catalog_product_bundle_selection',
        'catalog_product_bundle_price_index',
        'catalog_product_bundle_selection_price',
        'catalog_product_bundle_stock_index',
        'sales_invoice',
        'cataloginventory_stock_item',
        'catalog_product_option',
        'catalog_product_option_title',
        'catalog_product_option_price',
        'catalog_product_option_type_value',
        'catalog_product_option_type_title',
        'catalog_product_option_type_price',
        'salesrule_coupon',
        'cataloginventory_stock_status',
        'inventory_source_stock_link',
        'inventory_source_item',
        'inventory_reservation',
        'store',
        'catalog_product_entity_text',
        'eav_attribute_option_swatch'
    ];

    /**
     * @var GetListTables
     */
    private $getListTables;
    /**
     * @var Structure
     */
    private $structure;
    /**
     * @var \IWD\PaypalPos\Model\Backup\File
     */
    private $file;
    /**
     * @var Db
     */
    private $db;
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;
    /**
     * @var \IWD\PaypalPos\Model\Backup\Zip
     */
    private $zip;
    /**
     * @var StockIndexTableNameResolverInterface
     */
    private $stockIndexTableNameResolver;
    /**
     * @var StockRepositoryInterface
     */
    private $stockRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var TableMaintainer
     */
    private $tableMaintainer;

    /**
     * Creator constructor.
     * @param GetListTables $getListTables
     * @param Structure $structure
     * @param \IWD\PaypalPos\Model\Backup\File $file
     * @param Db $db
     * @param ResourceConnection $resourceConnection
     * @param \IWD\PaypalPos\Model\Backup\Zip $zip
     * @param StockIndexTableNameResolverInterface|null $stockIndexTableNameResolver
     * @param StockRepositoryInterface|null $stockRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param FilterBuilder $filterBuilder
     * @param StoreManagerInterface $storeManager
     * @param TableMaintainer $tableMaintainer
     */
    public function __construct(
        GetListTables $getListTables,
        Structure $structure,
        File $file,
        Db $db,
        ResourceConnection $resourceConnection,
        Zip $zip,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        FilterBuilder $filterBuilder,
        StoreManagerInterface $storeManager,
        TableMaintainer $tableMaintainer,
        StockIndexTableNameResolverInterface $stockIndexTableNameResolver = null,
        StockRepositoryInterface $stockRepository = null
    ) {
        $this->getListTables = $getListTables;
        $this->structure = $structure;
        $this->file = $file;
        $this->db = $db;
        $this->resourceConnection = $resourceConnection;
        $this->zip = $zip;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->storeManager = $storeManager;
        $this->tableMaintainer = $tableMaintainer;
        $this->stockIndexTableNameResolver = $stockIndexTableNameResolver;
        $this->stockRepository = $stockRepository;
    }

    public function execute()
    {
        $tables = $this->getListTables->execute();
        $neededTables = $this->getNeededTable();
        if ($this->stockIndexTableNameResolver) {
            $neededTables = $this->addInventoryTables($neededTables);
        }
        $neededTables = $this->addCategoryProductIndex($neededTables);
        $db = $this->getSqlLiteDb();
        $db->exec($this->structure->getHeader());
        foreach ($tables as $table) {
            if ($this->isNeededTable($table, $neededTables)) {
                $db->exec($this->structure->getTableCreateSql($table));
                $tableStatus = $this->db->getTableStatus($table);
                if ($this->hasRows($tableStatus)) {
                    $param = $this->getParam($tableStatus);
                    for ($i = 0; $i < $param['length']; $i++) {
                        $db->exec($this->structure->getTableData($table, $param['limit'], $i * $param['limit']));
                    }
                }
            }
        }
        $db->exec($this->structure->getFooter());
        $this->zip->create();
    }

    /**
     * @param $neededTables
     * @return mixed
     */
    public function addInventoryTables($neededTables)
    {

        $sortOrder = $this->sortOrderBuilder
            ->setField(StockInterface::STOCK_ID)
            ->setAscendingDirection()
            ->create();
        $filter = $this->filterBuilder
            ->setField(StockInterface::STOCK_ID)
            //default stock
            ->setValue(1)
            ->setConditionType('neq')
            ->create();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilters([$filter])
            ->setSortOrders([$sortOrder])
            ->create();

        $stockItems = $this->stockRepository->getList($searchCriteria);
        if ($stockItems->getTotalCount() > 0) {
            foreach ($stockItems->getItems() as $item) {
                $stockItemTableName = $this->stockIndexTableNameResolver->execute($item->getStockId());
                array_push($neededTables, $stockItemTableName);
            }
        }

        return $neededTables;
    }

    /**
     * @param $tableStatus
     * @return bool
     */
    private function hasRows($tableStatus)
    {
        return ($tableStatus->getRows()) ? true : false;
    }

    /**
     * @return string[]
     */
    public function getNeededTable(): array
    {
        $result = [];
        foreach ($this->tablesList as $table) {
            $result[] = $this->resourceConnection->getTableName($table);
        }
        return $result;
    }

    public function getSqlLiteDb()
    {
        $this->file->checkDir();
        $filePath = $this->file->removeFile();
        return new \SQLite3($filePath);
    }

    /**
     * @param $tableStatus
     * @return array
     */
    public function getParam($tableStatus): array
    {
        if ($tableStatus->getDataLength() > self::BUFFER_LENGTH) {
            if ($tableStatus->getAvgRowLength() < self::BUFFER_LENGTH) {
                $param['limit'] = floor(self::BUFFER_LENGTH / max($tableStatus->getAvgRowLength(), 1));
                $param['length'] = ceil($tableStatus->getRows() / $param['limit']);
            } else {
                $param['limit'] = 1;
                $param['length'] = $tableStatus->getRows();
            }
        } else {
            $param['limit'] = $tableStatus->getRows();
            $param['length'] = 1;
        }

        return $param;
    }

    /**
     * @param $table
     * @param array $neededTables
     * @return bool
     */
    private function isNeededTable($table, array $neededTables): bool
    {
        return in_array($table, $neededTables) ? true : false;
    }

    private function addCategoryProductIndex($neededTables)
    {
        foreach ($this->storeManager->getStores() as $store) {
            $table = $this->tableMaintainer->getMainTable((int)$store->getId());
            array_push($neededTables, $table);
        }
        return $neededTables;
    }
}
