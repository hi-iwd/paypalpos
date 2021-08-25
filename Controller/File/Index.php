<?php

namespace IWD\PaypalPos\Controller\File;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Webapi\Authorization;
use IWD\PaypalPos\Model\File\Zip;
use Magento\Framework\App\Response\Http\FileFactory;

class Index extends Action
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;
    /**
     * @var Authorization
     */
    private $authorization;
    /**
     * @var Zip
     */
    private $zip;
    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param Authorization $authorization
     * @param Zip $zip
     * @param FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Authorization $authorization,
        Zip $zip,
        FileFactory $fileFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->authorization = $authorization;
        $this->zip = $zip;
        $this->fileFactory = $fileFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute()
    {
        try {
            $filePath = $this->zip->getPath();
        } catch (\Magento\Framework\Webapi\Exception $e) {
            $result = $this->resultJsonFactory->create();
            $result->setHttpResponseCode(\Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR);
            $result->setData(['message' => $e->getMessage()]);
            return $result;
        }

            $fileName = basename((string)$filePath);
            $this->fileFactory->create(
                $fileName,
                [
                    'type' => 'filename',
                    'value' => $filePath
                ]
            );
        $result = $this->resultJsonFactory->create();
        $result->setHttpResponseCode(\Magento\Framework\Webapi\Exception::HTTP_FORBIDDEN);
        return $result;
    }
}
