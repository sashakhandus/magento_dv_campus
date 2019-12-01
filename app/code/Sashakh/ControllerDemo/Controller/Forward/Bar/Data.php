<?php
declare(strict_types=1);

namespace Sashakh\ControllerDemo\Controller\Forward\Bar;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Redirect;

class Data extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpGetActionInterface
{

    /**
     * @inheritDoc
     * https://sasha-khandus.local/sashakh-controller-demo/forward_bar/data
     */
    public function execute()
    {
        /** @var Redirect $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $response->setHttpResponseCode(301);
        $response->setPath(
             'sashakh-controller-demo/forward_bar/forward',
            [
                '_secure' => true,
                'first_name' => 'sasha',
                'last_name' => 'khandus',
                'url' => 'magento_dv_campus'
        ]);

        return $response;

    }
}