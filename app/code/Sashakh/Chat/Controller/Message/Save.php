<?php

declare(strict_types=1);

namespace Sashakh\Chat\Controller\Message;

use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Sashakh\Chat\Model\Chat;

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
     * @var \Sashakh\Chat\Model\ResourceModel\Chat
     */
    private $chatResource;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * Save constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Sashakh\Chat\Model\ChatFactory $chatFactory
     * @param \Sashakh\Chat\Model\ResourceModel\Chat $chatResource
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Sashakh\Chat\Model\ChatFactory $chatFactory,
        \Sashakh\Chat\Model\ResourceModel\Chat $chatResource,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->chatFactory = $chatFactory;
        $this->chatResource = $chatResource;
        $this->storeManager = $storeManager;
    }

    /**
     * @return JsonResult
     */
    public function execute(): JsonResult
    {
        $request = $this->getRequest();

        try {
            if (!$this->formKeyValidator->validate($request)) {
                throw new LocalizedException(__(
                    'Something went wrong. Probably you were away for quite a long time already. ' .
                    'Please, reload the page and try again.'
                ));
            }

            $websiteId = (int) $this->storeManager->getWebsite()->getId();
            $chatHash = $this->customerSession->getChatHash() ?? bin2hex(random_bytes(10));

            // @TODO: implement security layer when we get back to JS
            // @TODO: save data to customer session for guests

            /** @var Chat $chat */
            $chat = $this->chatFactory->create();
            $chat->setWebsiteId($websiteId)
                ->setAuthorType($request->getParam('authorType'))
                ->setAuthorName($request->getParam('authorName'))
                ->setMessage($request->getParam('message'))
                ->setChatHash($chatHash);

            if ($this->customerSession->isLoggedIn()) {
                $customerId = (int) $this->customerSession->getCustomerId();
                $chat->setCustomerId($customerId);
                $message = __('Saved!');
            } else {
                $message = __('Saved! Please, create account or log in have access to this chat later.');
            }

            $this->chatResource->save($chat);
            $this->customerSession->setChatHash($chat->getChatHash());
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
