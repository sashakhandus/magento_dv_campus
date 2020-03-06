<?php

namespace Sashakh\Chat\Block;

use Magento\Framework\Event\ManagerInterface as EventManager;
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
     * @var EventManager
     */
    private $eventManager;

    /**
     * Requests constructor.
     * @param \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $collectionFactory
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $collectionFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
    }

    public function getCustomerSession()
    {
        return $this->customerSession;
    }

    /**
     * @return Collection
     */
    public function getAllMessages(): Collection
    {
        //$this->eventManager->dispatch('customer_login', ['chat_hash' => $this->getCustomerSession()->getCustomerMessage()['chat_hash']]);

        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->getSelect();
        $collection->addFieldToFilter('chat_hash', ['eq' => $this->getCustomerSession()->getCustomerMessage()['chat_hash']]);
        $collection->setOrder('created_at', 'DESC');

        if ($limit = $this->getData('limit')) {
            $collection->setPageSize($limit);
        }

        return $collection;
    }
}
