<?php
declare(strict_types=1);
namespace Sashakh\Chat\Controller\Message;

use Sashakh\Chat\Model\Chat;
use Sashakh\Chat\Model\ResourceModel\Chat\Collection as ChatCollection;
use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\DB\Transaction;

class Save extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    /**
     * @var \Sashakh\Chat\Model\ChatFactory $chatFactory
     */
    private $chatFactory;

    /**
     * @var \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $preferenceCollectionFactory
     */
    private $chatCollectionFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * Save constructor.
     * @param \Sashakh\Chat\Model\ChatFactory $chatFactory
     * @param \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     */

    public function __construct(
        \Sashakh\Chat\Model\ChatFactory $chatFactory,
        \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->chatFactory = $chatFactory;
        $this->chatCollectionFactory = $chatCollectionFactory;
        $this->transactionFactory = $transactionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        // @TODO: implement security layer when we get back to JS
        // @TODO: save data to customer session for guests
        /** @var Transaction $transaction */
        $transaction = $this->transactionFactory->create();

        // Every fail should be controlled
        try {
            $websiteId = (int) $this->storeManager->getWebsite()->getId();

            /** @var ChatCollection $chatCollection */
            $chatCollection = $this->chatCollectionFactory->create();

            $chatCollection->addWebsiteFilter($websiteId);

            /** @var Chat $chat */
            $chat = $this->chatFactory->create();

            $chat->setWebsiteId($websiteId)
                ->setAuthorType($value)
                ->setAuthorName($value)
                ->setMessage($value);
            $transaction->addObject($chat);

            $transaction->save();
            $message = __('Saved!');
        } catch (\Exception $e) {
            $message = __('Your preferences can\'t be saved. Please, contact us if ypu see this message.');
        }

        /** @var JsonResult $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'message' => $message
        ]);
        return $response;
    }
}

function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
}