<?php
declare(strict_types=1);

namespace Sashakh\Chat\CustomerData;

use Sashakh\Chat\Model\ResourceModel\Chat\Collection as ChatCollection;

class Messages implements \Magento\Customer\CustomerData\SectionSourceInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory
     */
    private $chatCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterfyace
     */
    private $storeManager;

    /**
     * CustomerPreferences constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->customerSession = $customerSession;
        $this->chatCollectionFactory = $chatCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function getSectionData(): array
    {
        if ($this->customerSession->isLoggedIn()) {
            /** @var ChatCollection $chatCollection */
            $chatCollection = $this->chatCollectionFactory->create();
            $chatCollection->addCustomerFilter((int) $this->customerSession->getId())
                ->addWebsiteFilter((int) $this->storeManager->getWebsite()->getId());

            $data = $chatCollection->getData()[0];
        } else {
            $data = $this->customerSession->getData('customer_message') ?? [];
        }

        return $data;
    }
}