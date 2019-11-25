<?php
declare(strict_types=1);

namespace Sashakh\ControllerDemo\Controller\Forward\Bar;


class Baz extends \Magento\Framework\App\Action\Action
{

    /**
     * @inheritDoc
     * https://sasha-khandus.local/sashakh-controller-demo/forward_bar/baz/stringParameter/some%20string/integerParameter/12
     */
    public function execute()
    {
        throw new \RuntimeException('This is just a demo controller');
    }
}