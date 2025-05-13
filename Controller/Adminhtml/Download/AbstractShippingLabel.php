<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Controller\Adminhtml\Download;

use Edifference\Sendy\Service\Api;
use UnexpectedValueException;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

abstract class AbstractShippingLabel extends Action
{
    /**
     * @param Context                  $context
     * @param OrderRepositoryInterface $orderRepository
     * @param Api                      $apiService
     * @param RawFactory               $resultRawFactory
     */
    public function __construct(
        Context                                     $context,
        protected readonly OrderRepositoryInterface $orderRepository,
        protected readonly Api                      $apiService,
        protected readonly RawFactory               $resultRawFactory,
    ) {
        parent::__construct($context);
    }

    /**
     * Stream the shipping label PDF
     *
     * @param string $fileName
     * @param string $base64Content
     * @return Raw
     * @throws Exception
     */
    protected function streamFile(
        string $fileName,
        string $base64Content
    ): Raw {
        // phpcs:ignore Magento2.Functions.DiscouragedFunction
        $decodedContent = base64_decode($base64Content);
        if ($decodedContent === false) {
            throw new UnexpectedValueException('Invalid Base64 content.');
        }
        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setHeader(
            'Content-Type',
            'application/pdf',
            true
        );
        $resultRaw->setHeader(
            'Content-Disposition',
            'download; filename="' . $fileName . '"',
            true
        );
        $resultRaw->setContents($decodedContent);
        return $resultRaw;
    }
}
