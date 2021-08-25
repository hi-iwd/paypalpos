<?php

namespace IWD\PaypalPos\Cron;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\FlagManager;
use IWD\PaypalPos\Model\BackupFlag;
use IWD\PaypalPos\Model\Backup\Creator;
use Magento\Framework\Stdlib\DateTime\DateTime;
use IWD\PaypalPos\Model\Backup\Zip;
use Magento\Framework\Filesystem;

class Backup
{
    /**
     * @var FlagManager
     */
    private $flagManager;
    /**
     * @var Creator
     */
    private $creator;
    /**
     * @var DateTime
     */
    private $dateTime;
    /**
     * @var Zip
     */
    private $zip;

    /**
     * @var Filesystem\Directory\WriteInterface
     */
    private $varDirectory;
    /**
     * @var BackupFlag
     */
    private $backupFlag;

    /**
     * Backup constructor.
     * @param FlagManager $flagManager
     * @param Creator $creator
     * @param DateTime $dateTime
     * @param Zip $zip
     * @param Filesystem $filesystem
     * @param BackupFlag $backupFlag
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        FlagManager $flagManager,
        Creator $creator,
        DateTime $dateTime,
        Zip $zip,
        Filesystem $filesystem,
        BackupFlag $backupFlag
    ) {
        $this->flagManager = $flagManager;
        $this->creator = $creator;
        $this->dateTime = $dateTime;
        $this->zip = $zip;
        $this->backupFlag = $backupFlag;
        $this->varDirectory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->checkHtaccess();
    }

    /**
     * execute
     */
    public function execute()
    {
        if ($this->flagManager->getFlagData(BackupFlag::FLAG)
            && !$this->flagManager->getFlagData(BackupFlag::INPROGRESS)
            && (!$this->backupFlag->checkDate() || !$this->zip->isFile())
        ) {
            $this->createBackup();
        }
        $this->flagManager->deleteFlag(BackupFlag::FLAG);
    }

    public function check()
    {
        if (!$this->flagManager->getFlagData(BackupFlag::INPROGRESS)
            && (!$this->backupFlag->checkDate() || !$this->zip->isFile())
        ) {
            $this->flagManager->deleteFlag(BackupFlag::FLAG);
            $this->createBackup();
        }
    }

    private function createBackup()
    {
        set_time_limit(0);
        ignore_user_abort(true);
        $lastBackupDate = $this->dateTime->timestamp();
        $this->flagManager->saveFlag(BackupFlag::INPROGRESS, 1);
        $this->creator->execute();
        $this->flagManager->deleteFlag(BackupFlag::INPROGRESS);
        $this->flagManager->deleteFlag(BackupFlag::FLAG);
        $this->flagManager->saveFlag(BackupFlag::LAST_BACKUP_DATE, $lastBackupDate);
    }

    private function checkHtaccess()
    {
        $name = '.htaccess';
        if (!$this->varDirectory->isFile($name)) {
            $this->varDirectory->writeFile($name, 'deny from all');
        }
    }
}
