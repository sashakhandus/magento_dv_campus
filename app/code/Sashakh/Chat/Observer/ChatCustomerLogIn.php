<?php

declare(strict_types=1);

namespace Sashakh\Chat\Observer;

use Magento\Framework\DB\Transaction;
use Magento\Framework\Event\Observer;
use Sashakh\Chat\Model\Chat;
use Sashakh\Chat\Model\ResourceModel\Chat\Collection as MessageCollection;

class ChatCustomerLogIn implements \Magento\Framework\Event\ObserverInterface
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
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * Requests constructor.
     * @param \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $collectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    public function __construct(
        \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $collectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\DB\TransactionFactory $transactionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
        $this->transactionFactory = $transactionFactory;
    }

    /**
     * @param Observer $observer
     * @throws \Exception
     */
    public function execute(Observer $observer): void
    {
        $customerId = $this->customerSession->getCustomerId();

        /** @var MessageCollection $existingMessageCollection */
        $existingMessageCollection = $this->collectionFactory->create();
        $existingMessageCollection->addFieldToFilter('customer_id', $this->customerSession->getCustomerId())
            ->setPageSize(1);

        /** @var Chat $existingMessage */
        $existingMessage = $existingMessageCollection->getFirstItem();
        $oldChatHash = (string) $existingMessage->getChatHash();

        if ($chatHash = $this->customerSession->getChatHash()) {
            /** @var MessageCollection $collection */
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter('chat_hash', $chatHash);
            $collection->setOrder('created_at', 'DESC');

            /** @var Transaction $transaction */
            $transaction = $this->transactionFactory->create();

            /** @var Chat $message */
            foreach ($collection as $message) {
                if ($message->getAuthorType() === 1) {
                    $message->setData('author_id', $customerId);
                }

                if ($oldChatHash) {
                    $message->setChatHash($oldChatHash);
                }

                $transaction->addObject($message);
            }

            $transaction->save();
        }

        if ($oldChatHash) {
            $this->customerSession->setChatHash($oldChatHash);
        }
    }
}
