<?php

namespace IWD\PaypalPos\Controller\Invoices;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use IWD\PaypalPos\Model\Service\InvoicePdfGeneratorService;
use Magento\Webapi\Model\Authorization\TokenUserContext;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Pdf extends Action
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;
    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoiceRepository;

    /**
     * @var InvoicePdfGeneratorService
     */
    private $invoicePdfGeneratorService;

    /**
     * @var TokenUserContext
     */
    private $tokenUserContext;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param FileFactory $fileFactory
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param InvoicePdfGeneratorService $invoicePdfGeneratorService
     * @param TokenUserContext $tokenUserContext
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        FileFactory $fileFactory,
        InvoiceRepositoryInterface $invoiceRepository,
        InvoicePdfGeneratorService $invoicePdfGeneratorService,
        TokenUserContext $tokenUserContext
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->fileFactory = $fileFactory;
        $this->invoiceRepository = $invoiceRepository;
        $this->invoicePdfGeneratorService = $invoicePdfGeneratorService;
        $this->tokenUserContext = $tokenUserContext;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Webapi\Exception
     * @throws \Zend_Pdf_Exception
     */
    public function execute()
    {
        $invoiceId = $this->getRequest()->getParam('invoice_id');
        try {
            $this->checkAccess();
            $invoice = $this->invoiceRepository->get((int)$invoiceId);
            $pdf = $this->invoicePdfGeneratorService->execute($invoice);
        } catch (\Magento\Framework\Webapi\Exception $e) {
            $result = $this->resultJsonFactory->create();
            $result->setHttpResponseCode(\Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR);
            $result->setData(['message' => $e->getMessage()]);
            return $result;
        }

        return  $this->fileFactory->create(
            'invoice' . time() . '.pdf',
            [
                'type' => 'string',
                'value' => $pdf->render(),
                'rm' => true
            ],
            DirectoryList::VAR_DIR,
            'application/pdf'
        );
    }

    private function checkAccess()
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

        return true;
    }
}
