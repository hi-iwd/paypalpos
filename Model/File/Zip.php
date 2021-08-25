<?php

namespace IWD\PaypalPos\Model\File;

use IWD\PaypalPos\Model\Backup\File;
use IWD\PaypalPos\Model\Backup\Zip as ZipCreator;
use IWD\PaypalPos\Model\BackupFlag;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\FlagManager;
use Magento\Framework\Filesystem\Driver\File as DriverFile;
use Magento\Webapi\Model\Authorization\TokenUserContext;

class Zip
{
    /**
     * @var File
     */
    private $file;

    /**
     * @var DriverInterface
     */
    private $driverFile;
    /**
     * @var ZipCreator
     */
    private $zipCreator;
    /**
     * @var TokenUserContext
     */
    private $tokenUserContext;
    /**
     * @var FlagManager
     */
    private $flagManager;

    /**
     * Zip constructor.
     * @param File $file
     * @param FlagManager $flagManager
     * @param DriverFile $driverFile
     * @param ZipCreator $zipCreator
     * @param TokenUserContext $tokenUserContext
     */
    public function __construct(
        File $file,
        FlagManager $flagManager,
        DriverFile $driverFile,
        ZipCreator $zipCreator,
        TokenUserContext $tokenUserContext
    ) {
        $this->file = $file;
        $this->flagManager = $flagManager;
        $this->driverFile = $driverFile;
        $this->zipCreator = $zipCreator;
        $this->tokenUserContext = $tokenUserContext;
    }

    /**
     * @param array|bool|float|int|object|string|null $data
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function getPath()
    {
        $this->isAdminUser();
        $this->checkFlag();
        $filePath = $this->zipCreator->getFilePath();
        if (!$this->file->isFile($filePath)) {
            throw new \Magento\Framework\Webapi\Exception(__('The file doesn\'t exist'));
        }
        return $filePath;
    }

    /**
     * @throws \Magento\Framework\Webapi\Exception
     */
    private function checkFlag()
    {
        if ($this->flagManager->getFlagData(BackupFlag::FLAG)
            && !$this->flagManager->getFlagData(BackupFlag::INPROGRESS)
        ) {
            throw new \Magento\Framework\Webapi\Exception(__('Awaiting backup'));
        }
        if ($this->flagManager->getFlagData(BackupFlag::FLAG)
            && $this->flagManager->getFlagData(BackupFlag::INPROGRESS)
        ) {
            throw new \Magento\Framework\Webapi\Exception(__('Creation in progress'));
        }
    }

    /**
     * @throws \Magento\Framework\Webapi\Exception
     */
    private function isAdminUser()
    {
        if ($this->tokenUserContext->getUserType() != UserContextInterface::USER_TYPE_INTEGRATION
        || !$this->tokenUserContext->getUserId()
        ) {
            throw new \Magento\Framework\Webapi\Exception(
                __('Access denied'),
                \Magento\Framework\Webapi\Exception::HTTP_FORBIDDEN,
                \Magento\Framework\Webapi\Exception::HTTP_FORBIDDEN,
                [],
                'Access denied'
            );
        }
    }
}
