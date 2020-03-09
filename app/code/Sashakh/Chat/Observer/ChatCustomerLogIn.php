<?php

declare(strict_types=1);
namespace Sashakh\Chat\Observer;

use Magento\Framework\DB\Transaction;
use Magento\Framework\Event\Observer;

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
     * @param array $data
     */
    public function __construct(
        \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $collectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
        $this->transactionFactory = $transactionFactory;
    }

    /**
     * @return \Magento\Customer\Model\Session
     */
    public function getCustomerSession()
    {
        return $this->customerSession;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $customerId = $this->getCustomerSession()->getCustomer()->getId();

        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->getSelect();
        $collection->addFieldToFilter('chat_hash', ['eq' => $this->getCustomerSession()->getCustomerMessage()['chat_hash']]);
        $collection->setOrder('created_at', 'DESC');

        foreach ($collection as $item) {
            /** @var Transaction $transaction */
            $transaction = $this->transactionFactory->create();
            $item->setData('customer_id', $customerId);
            $transaction->addObject($item);
            $transaction->save();
        }
    }
}
