<?php
declare(strict_types=1);

namespace Sashakh\Chat\Model;

use Magento\Framework\Exception\LocalizedException;

/**
 * @method int getMessageId()
 * @method $this setMessageId(int $messageId)
 * @method int getAuthorType()
 * @method $this setAuthorType(int $authorType)
 * @method string getAuthorName()
 * @method $this setAuthorName(string $authorName)
 * @method string getMessage()
 * @method $this setMessage(string $message)
 * @method int getWebsiteId()
 * @method $this setWebsiteId(int $websiteId)
 */

class Chat extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Chat constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(\Sashakh\Chat\Model\ResourceModel\Chat::class);
    }

    /**
     * Validate website_id before saving data
     * Do not fill in data automatically not to break install scripts or import/export that may work from the
     * crontab area or from the CLI - e.g. follow the "Let it fail" principle
     *
     * @return $this
     * @throws LocalizedException
     */
    public function beforeSave(): self
    {
        // Allow changing data before save
        parent::beforeSave();
        // @TODO: see the AbstractModel::validateBeforeSave() method and its' implementation for better implementation
        $this->validate();
        return $this;
    }
    /**
     * Allow overriding this method via plugins by making it public.
     * This method must be called in the ::beforeSave() method to guarantee that it is executed.
     * Moving the call to a controller means that somebody will forget to do the same in other place.
     * Write error-prone code!
     *
     * @throws LocalizedException
     */
    public function validate(): void
    {
        if (!$this->getWebsiteId()) {
            throw new LocalizedException(__('Can\'t save customer preferences: %s is not set.', 'website_id'));
        }
    }
}