<?php

namespace Genesisoft\Base\Plugin\Block;

use Magento\Backend\App\Action\Context;
use Magento\Cms\Api\BlockRepositoryInterface as BlockRepository;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class InlineEdit
{
    /**
     * @var \Magento\Cms\Api\BlockRepositoryInterface
     */
    protected $blockRepository;

    /**
     * Request object
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * @param BlockRepository $blockRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        RequestInterface $httpRequest,
        BlockRepository $blockRepository,
        JsonFactory $jsonFactory
    ){
        $this->blockRepository = $blockRepository;
        $this->jsonFactory = $jsonFactory;
        $this->request = $httpRequest;
    }

    public function aroundExecute(\Magento\Cms\Controller\Adminhtml\Block\InlineEdit $subject, callable $proceed)
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->request->getParam('isAjax')) {
            $postItems = $this->request->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $blockId) {
                    /** @var \Magento\Cms\Model\Block $block */
                    $block = $this->blockRepository->getById($blockId);
                    try {
                        if($block->getIdentifier() == 'shipping_information' && $postItems[$blockId]['identifier'] != 'shipping_information'){
                            $messages[] = __('Block %1 cannot be changed because it is used by the system.', $block->getIdentifier());
                            $error = true;
                        }else{
                            $block->setData(array_merge($block->getData(), $postItems[$blockId]));
                            $this->blockRepository->save($block);
                        }
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithBlockId(
                            $block,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
