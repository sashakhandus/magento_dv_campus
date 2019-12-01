<?php
declare(strict_types=1);

namespace Sashakh\ControllerDemo\Controller\Forward\Bar;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;

class HtmlResponse extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpGetActionInterface
{

    /**
     * @inheritDoc
     * https://sasha-khandus.local/sashakh-controller-demo/forward_bar/htmlResponse
     */
    public function execute()
    {
        /** @var Page $response */
        return $response = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}