<?php
declare(strict_types=1);

namespace Sashakh\Catalog\Controller\FooFoo\Bar;

class Baz extends \Magento\Framework\App\Action\Action
{
    /**
     * @inheritDoc
     * https://sasha-khandus.local/some-pretty-url/fooFoo_bar/baz/stringParameter/some%20string/integerValue/12
     */
    public function execute()
    {
        $this->getRequest()->getParams();
        throw new \RuntimeException('This is just a demo controller');
    }

}