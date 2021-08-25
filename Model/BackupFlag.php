<?php

namespace IWD\PaypalPos\Model;

use Magento\Framework\FlagManager;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class BackupFlag
{
    const FLAG = 'pos_backup';

    const INPROGRESS = 'pos_run';

    const LAST_BACKUP_DATE = "pos_last_backup";
    /**
     * @var FlagManager
     */
    private $flagManager;

    /**
     * @var Request
     */
    private $request;
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * BackupFlag constructor.
     * @param FlagManager $flagManager
     * @param Request $request
     */
    public function __construct(
        FlagManager $flagManager,
        Request $request,
        DateTime $dateTime
    ) {
        $this->flagManager = $flagManager;
        $this->request = $request;
        $this->dateTime = $dateTime;
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function addFlag()
    {
        if ($this->isAcceptHeader()) {
            return false;
        }
        $checkDate = $this->checkDate();
        if (!$this->flagManager->getFlagData(self::INPROGRESS)
            && !$checkDate
        ) {
            $this->flagManager->saveFlag(self::FLAG, 1);
            return true;
        } elseif (!$this->flagManager->getFlagData(self::INPROGRESS)
            && $checkDate
        ) {
            $this->flagManager->deleteFlag(self::FLAG);
            return true;
        }

        if ($this->flagManager->getFlagData(self::INPROGRESS)) {
            throw new \Magento\Framework\Webapi\Exception(
                __('Creation in progress'),
                0,
                \Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR
            );
        }
    }

    /**
     * @return bool
     */
    public function isAcceptHeader()
    {
        return $this->request->getHeader('Accept') == 'application/zip';
    }

    /**
     * @return bool
     */
    public function checkDate()
    {
        $lastDate = $this->flagManager->getFlagData(self::LAST_BACKUP_DATE);
        if (!$lastDate) {
            return false;
        }
        $diff = $this->dateTime->timestamp() - $lastDate;
        $hour = $diff / 60 / 60;
        //older than 46 hours
        if ($hour > 46) {
            return false;
        }
        return true;
    }
}
