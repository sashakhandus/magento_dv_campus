<?php
declare(strict_types=1);
namespace Sashakh\Chat\Controller\Message;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\DB\Transaction;
use Sashakh\Chat\Model\Chat;
use Sashakh\Chat\Model\ResourceModel\Chat\Collection as ChatCollection;

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
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * Save constructor.
     * @param \Sashakh\Chat\Model\ChatFactory $chatFactory
     * @param \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param  \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     */

    public function __construct(
        \Sashakh\Chat\Model\ChatFactory $chatFactory,
        \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
    ) {
        parent::__construct($context);
        $this->chatFactory = $chatFactory;
        $this->chatCollectionFactory = $chatCollectionFactory;
        $this->transactionFactory = $transactionFactory;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
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

        /** @var Http $request */
        $request = $this->getRequest();

        // Every fail should be controlled
        try {
            $websiteId = (int) $this->storeManager->getWebsite()->getId();

            /** @var ChatCollection $chatCollection */
            $chatCollection = $this->chatCollectionFactory->create();

            $chatCollection->addWebsiteFilter($websiteId);

            /** @var Chat $chat */
            $chat = $this->chatFactory->create();

            $chat->setWebsiteId($websiteId)
                    ->setAuthorType($request->getParam('authorType'))
                    ->setAuthorName($request->getParam('authorName'))
                    ->setMessage($request->getParam('message'))
                    ->setChatHash(bin2hex(random_bytes(10)));
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
