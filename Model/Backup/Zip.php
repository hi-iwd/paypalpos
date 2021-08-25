<?php

namespace IWD\PaypalPos\Model\Backup;

use IWD\PaypalPos\Model\Backup\File;

class Zip
{
    /**
     * @var \IWD\PaypalPos\Model\Backup\File
     */
    private $file;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Zip constructor.
     * @param \IWD\PaypalPos\Model\Backup\File $file
     * @param LoggerInterface $logger
     */
    public function __construct(
        File $file
    ) {
        $this->file = $file;
    }

    /**
     * create zip file
     */
    public function create()
    {
        $zip = new \ZipArchive();
        $zip->open($this->getFilePath(), \ZipArchive::CREATE);
        $zip->addFile($this->file->getFilePath(), File::POS_FILE);
        $zip->close();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getFilePath()
    {
        return $this->file->getDirPath().File::POS_FILE_ZIP;
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function isFile()
    {
        return $this->file->isFile($this->getFilePath());
    }
}
