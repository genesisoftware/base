<?php

namespace Genesisoft\Base\Plugin\Block;

use Magento\Cms\Api\BlockRepositoryInterface as BlockRepository;
use Magento\Framework\App\RequestInterface;

class Delete
{
    /**
     * Request object
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Cms\Api\BlockRepositoryInterface
     */
    protected $blockRepository;

    /**
     * Delete constructor.
     *
     * @param RequestInterface $httpRequest
     * @param BlockRepository $blockRepository
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        RequestInterface $httpRequest,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        BlockRepository $blockRepository,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
    )
    {
        $this->request = $httpRequest;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->blockRepository = $blockRepository;
        $this->messageManager = $messageManager;
    }

    public function aroundExecute(\Magento\Cms\Controller\Adminhtml\Block\Delete $subject, callable $proceed)
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = $this->request->getParam('block_id');
        if ($id) {
            try {
                $model = $this->blockRepository->getById($id);

                if ($model->getIdentifier() === 'shipping_information') {
                    $this->messageManager->addErrorMessage(__('This block cannot be deleted because it is used by the system.'));
                    return $resultRedirect->setPath('*/*/');
                }
            } catch (\Exception $e) {
                return $proceed();
            }
        }

        return $proceed();
    }
}
