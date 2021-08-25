<?php

namespace IWD\PaypalPos\Model\Backup;

use Magento\Framework\FlagManager;
use IWD\PaypalPos\Model\BackupFlag;
use IWD\PaypalPos\Api\BackupManagementInterface;

class Date implements BackupManagementInterface
{
    /**
     * @var FlagManager
     */
    private $flagManager;

    /**
     * Date constructor.
     * @param FlagManager $flagManager
     */
    public function __construct(
        FlagManager $flagManager
    ) {
        $this->flagManager = $flagManager;
    }

    /**
     * @return array
     */
    public function getLastDate()
    {
        return ['timestamp'=>$this->flagManager->getFlagData(BackupFlag::LAST_BACKUP_DATE)];
    }
}

