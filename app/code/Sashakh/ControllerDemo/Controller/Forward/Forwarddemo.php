<?php
declare(strict_types=1);

namespace Sashakh\ControllerDemo\Controller\Forward;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Forward;

class Forwarddemo extends \Magento\Framework\App\Action\Action

{
    /**
     * @inheritDoc
     * https://sasha-khandus.local/sashakh-controller-demo/forward/forwarddemo
     */
    public function execute()
    {

        /** @var Forward $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);

        $response
            ->setController('sashakh-controller-demo')
            ->setParams(['first_name' => 'sasha', 'last_name' => 'khandus', 'url' => 'magento_dv_campus'])
            ->forward('data');

        return $response;

    }
}