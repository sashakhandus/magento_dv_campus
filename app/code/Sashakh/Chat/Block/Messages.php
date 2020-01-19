<?php

namespace Sashakh\Chat\Block;

use Sashakh\Chat\Model\ResourceModel\Chat\Collection;


class Messages  extends \Magento\Framework\View\Element\Template

{
    /**
     * @var \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory
     */
    private $collectionFactory;

    /**
     * Requests constructor.
     * @param \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $collectionFactory
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $collectionFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return Collection
     */
    public function getAllMessages(): Collection
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->getSelect();
        /*$collection->setOrder('created_at', 'DESC');

        if ($limit = $this->getData('limit')) {
            $collection->setPageSize($limit);
        }*/

        return $collection;
    }
}