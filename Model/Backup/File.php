<?php

namespace IWD\PaypalPos\Model\Backup;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File as FileIo;

class File
{
    const POS_DIR = 'pos';

    const POS_FILE = 'file.db';

    const POS_FILE_ZIP = 'file.zip';

    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var FileIo
     */
    private $fileIo;

    /**
     * File constructor.
     * @param DirectoryList $directoryList
     * @param FileIo $fileIo
     */
    public function __construct(
        DirectoryList $directoryList,
        FileIo $fileIo
    ) {
        $this->directoryList = $directoryList;
        $this->fileIo = $fileIo;
    }

    /**
     * @return string
     */
    public function checkDir()
    {
        $posDir = $this->getDirPath();
        if (!$this->fileIo->fileExists($posDir, false)) {
            $this->fileIo->mkdir($posDir, 0755);
        }
        return $posDir;
    }

    /**
     * @return string
     */
    public function removeFile()
    {
        $filePath = $this->getFilePath();
        if ($this->fileIo->fileExists($filePath, true)) {
            $this->fileIo->rm($filePath);
        }
        return $filePath;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->getDirPath().self::POS_FILE;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getDirPath()
    {
        $varDir = $this->directoryList->getPath('var');
        return $varDir.DIRECTORY_SEPARATOR.self::POS_DIR.DIRECTORY_SEPARATOR;
    }

    /**
     * @param $filePath
     * @return bool
     */
    public function isFile($filePath)
    {
        if ($this->fileIo->fileExists($filePath, true)) {
            return true;
        }

        return false;
    }
}
