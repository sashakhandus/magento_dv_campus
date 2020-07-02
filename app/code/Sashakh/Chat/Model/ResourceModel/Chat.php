<?php

declare(strict_types=1);

namespace Sashakh\Chat\Model\ResourceModel;

class Chat extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('sashakh_chat', 'message_id');
    }

}