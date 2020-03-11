<?php
declare(strict_types=1);
namespace Sashakh\Chat\Block;

use Sashakh\Chat\Model\ResourceModel\Chat\Collection;

class Messages extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory
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
     * @return \Magento\Customer\Model\Session
     */
    public function getCustomerSession()
    {
        return $this->customerSession;
    }

    /**
     * @return String
     */
    public function getAllMessagesJson(): string
    {
        if ($this->getCustomerSession()->getCustomer()->getId()) {
            /** @var Collection $collection */
            $collection = $this->collectionFactory->create();
            $collection->getSelect();
            $collection->addFieldToFilter('chat_hash', ['eq' => $this->getCustomerSession()->getCustomer()->getId()]);
            $collection->setOrder('created_at', 'DESC');
        } else {
            /** @var Collection $collection */
            $collection = $this->collectionFactory->create();
            $collection->getSelect();
            $collection->addFieldToFilter('chat_hash', ['eq' => $this->getCustomerSession()->getCustomerMessage()['chat_hash']]);
            $collection->setOrder('created_at', 'DESC');
        }

        if ($limit = $this->getData('limit')) {
            $collection->setPageSize($limit);
        }

        $messages = [];

        /** @var Messages $messages */
        if ($collection) {
            foreach ($collection as $item) {
                $messages[] = [
                    'author_type' => $item->getAuthorType(),
                    'author_name' => $item->getAuthorName(),
                    'message' => $item->getMessage(),
                    'created_at' => $item->getCreatedAt()
                ];
            }
        }

        return $this->jsonSerializer->serialize($messages);
    }
}
