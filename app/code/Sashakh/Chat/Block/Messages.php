<?php

declare(strict_types=1);

namespace Sashakh\Chat\Block;

use Sashakh\Chat\Model\ResourceModel\Chat\Collection;

class Messages extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @var \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $collectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $jsonSerializer;

    /**
     * Requests constructor.
     * @param \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $collectionFactory,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @return string
     */
    public function getAllMessagesJson(): string
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('chat_hash', $this->customerSession->getChatHash());
        $collection->setOrder('created_at', 'DESC');

        if ($limit = $this->getData('limit')) {
            $collection->setPageSize($limit);
        }

        $messages = [];

        /** @var  $message */
        foreach ($collection as $message) {
            $messages[] = [
                'message_id' => $message->getMessageId(),
                'author_type' => $message->getAuthorType()
            ];
        }

        return $this->jsonSerializer->serialize($messages);
    }
}