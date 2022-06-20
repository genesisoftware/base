<?php

namespace Genesisoft\Base\Plugin\Model\Block;

use Magento\Framework\App\RequestInterface;

class DataProvider
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * DefaultResponseProvider constructor.
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }
    public function afterGetData($subject, $data)
    {
        $block_id = $this->request->getParam('block_id');
        if(!empty($data[$block_id])){
            if(!empty($data[$block_id]['identifier'])){
                if($data[$block_id]['identifier'] === 'shipping_information'){
                    $data[$block_id]['hide_indentifier'] = true;
                }else{
                    $data[$block_id]['hide_indentifier'] = false;
                }
            }
        }
        return $data;
    }
}
