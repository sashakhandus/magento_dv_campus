<?php
declare(strict_types=1);

namespace Sashakh\ControllerDemo\Controller\Forward\Bar;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Json;

class Forward extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpGetActionInterface
{

    /**
     * @inheritDoc
     * https://sasha-khandus.local/sashakh-controller-demo/forward_bar/forward/first_name/sasha/last_name/khandus/url/magento_dv_campus/
     */
    public function execute()
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();

        /** @var Json $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'firs name' => $request->getParam('first_name'),
            'last name' => $request->getParam('last_name'),
            'url' => $request->getParam('url'),
        ]);

        return $response;

    }
}