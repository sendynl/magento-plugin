<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Block\System\Config\Form;

use Edifference\Sendy\Service\Api;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use Sendy\Api\ApiException;

/**
 * @copyright (c) eDifference 2024
 */
class Oauth extends Field
{
    private const BUTTON_TEMPLATE = 'system/config/oauth/button.phtml';

    /**
     * @param Context                 $context
     * @param Api                     $apiService
     * @param array                   $data
     * @param SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        Context              $context,
        private readonly Api $apiService,
        array                $data = [],
        ?SecureHtmlRenderer  $secureRenderer = null
    ) {
        parent::__construct(
            $context,
            $data,
            $secureRenderer
        );
    }

    /**
     * Prepare block layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate(self::BUTTON_TEMPLATE);
        return $this;
    }

    /**
     * Get the HTML data
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $data = [
            'is_connected' => true,
            'connect_label' => __('Connect to Sendy'),
            'redirect_url' => $this->getUrl("edifference_sendy/oauth/initialize"),
            'clear_url' => $this->getUrl("edifference_sendy/oauth/clear"),
        ];
        try {
            $connection = $this->apiService->getSendyConnection();
            $data['info'] = $connection->me->get();
        } catch (NotFoundException $e) {
            $data['is_connected'] = false;
        } catch (ApiException $e) {
            $data['message'] = $e->getMessage();
            $data['is_connected'] = false;
        }
        $this->addData($data);
        return $this->_toHtml();
    }
}
