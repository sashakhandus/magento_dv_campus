<?php
declare(strict_types=1);
namespace Sashakh\Chat\Controller\Message;

use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\DB\Transaction;
use Magento\Framework\Exception\LocalizedException;
use Sashakh\Chat\Model\Chat;
use Sashakh\Chat\Model\ResourceModel\Chat\Collection as ChatCollection;

class Save extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     */
    private $formKeyValidator;

    /**
     * @var \Sashakh\Chat\Model\ChatFactory $chatFactory
     */
    private $chatFactory;

    /**
     * @var \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory
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
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Save constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Sashakh\Chat\Model\ChatFactory $chatFactory
     * @param \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\Action\Context $context
     */

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Sashakh\Chat\Model\ChatFactory $chatFactory,
        \Sashakh\Chat\Model\ResourceModel\Chat\CollectionFactory $chatCollectionFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->chatFactory = $chatFactory;
        $this->chatCollectionFactory = $chatCollectionFactory;
        $this->transactionFactory = $transactionFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     */

    public function execute()
    {
        $request = $this->getRequest();

        try {
            if (!$this->formKeyValidator->validate($request)) {
                throw new LocalizedException(__('Something went wrong. Probably you were away for quite a long time already. Please, reload the page and try again.'));
            }

            $customerId = (int)$this->customerSession->getId();
            $websiteId = (int)$this->storeManager->getWebsite()->getId();

            $chatHash = $this->customerSession->getCustomerMessage()['chat_hash'] ?? bin2hex(random_bytes(10));

            if ($customerId) {

                /** @var ChatCollection $chatCollection */
                $chatCollection = $this->chatCollectionFactory->create();
                $chatCollection->addCustomerFilter($customerId)
                    ->addWebsiteFilter($websiteId);

                // @TODO: implement security layer when we get back to JS
                // @TODO: save data to customer session for guests
                /** @var Transaction $transaction */
                $transaction = $this->transactionFactory->create();

                /** @var Chat $chat */
                $chat = $this->chatFactory->create();

                $chat->setWebsiteId($websiteId)
                    ->setAuthorType($request->getParam('authorType'))
                    ->setAuthorName($request->getParam('authorName'))
                    ->setMessage($request->getParam('message'))
                    ->setChatHash($this->customerSession->getCustomerId());
                $transaction->addObject($chat);

                $transaction->save();

                $this->customerSession->setCustomerMessage($chat->getData());

                $message = __('Saved!');
            } else {

                /** @var Transaction $transaction */
                $transaction = $this->transactionFactory->create();

                /** @var ChatCollection $chatCollection */
                $chatCollection = $this->chatCollectionFactory->create();
                $chatCollection->addCustomerFilter($customerId)
                    ->addWebsiteFilter($websiteId);

                /** @var Chat $chat */
                $chat = $this->chatFactory->create();

                $chat->setWebsiteId($websiteId)
                    ->setAuthorType($request->getParam('authorType'))
                    ->setAuthorName($request->getParam('authorName'))
                    ->setMessage($request->getParam('message'))
                    ->setChatHash($chatHash);
                $transaction->addObject($chat);

                $transaction->save();

                $this->customerSession->setCustomerMessage($chat->getData());

                $message = __('Saved!  Please, log in to save them messages.');
            }
        } catch (LocalizedException $e) {
            $message = $e->getMessage();
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
