<?php

declare(strict_types=1);

namespace Sashakh\Chat\Model\ResourceModel\Chat;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(
            \Sashakh\Chat\Model\Chat::class,
            \Sashakh\Chat\Model\ResourceModel\Chat::class
        );
    }

    /**
     * @param int $websiteId
     * @return Collection
     */
    public function addWebsiteFilter(int $websiteId): self
    {
        return $this->addFieldToFilter('website_id', $websiteId);
    }

    /**
     * @param int $customerId
     * @return Collection
     */
    public function addCustomerFilter(int $customerId): self
    {
        return $this->addFieldToFilter('customer_id', $customerId);
    }
}
