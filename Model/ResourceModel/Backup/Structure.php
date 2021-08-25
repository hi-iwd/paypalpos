<?php

namespace IWD\PaypalPos\Model\ResourceModel\Backup;

use Magento\Backup\Model\ResourceModel\Db as BackupDb;
use IWD\PaypalPos\Model\Backup\Convertor;
use IWD\PaypalPos\Model\ResourceModel\Helper as ResourceHelper;

class Structure
{
    /**
     * @var BackupDb
     */
    private $backupDb;
    /**
     * @var Convertor
     */
    private $convertor;
    /**
     * @var ResourceHelper
     */
    private $resourceHelper;

    /**
     * Structure constructor.
     * @param BackupDb $backupDb
     * @param Convertor $convertor
     * @param ResourceHelper $resourceHelper
     */
    public function __construct(
        BackupDb $backupDb,
        Convertor $convertor,
        ResourceHelper $resourceHelper
    ) {
        $this->backupDb = $backupDb;
        $this->convertor = $convertor;
        $this->resourceHelper = $resourceHelper;
    }

    public function getHeader()
    {
        $header  = "PRAGMA synchronous = OFF;";
        $header .= "PRAGMA journal_mode = MEMORY;";
        $header .= "BEGIN TRANSACTION;";
        return $header;
    }

    public function getFooter()
    {
        return "END TRANSACTION;";
    }

    public function getTableCreateSql($table)
    {
        return $this->convertor->replaceTableSql($this->backupDb->getTableCreateSql($table, false));
    }

    public function getTableData($table, $count, $offset)
    {
        return $this->resourceHelper->getPartInsertSql($table, $count, $offset);
    }
}
