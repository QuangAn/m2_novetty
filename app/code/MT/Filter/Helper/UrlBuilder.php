<?php

namespace MT\Filter\Helper;


use Magento\Framework\App\Helper\Context;
use Magento\Framework\Registry;

class UrlBuilder extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Amasty\Shopby\Helper\FilterSetting
     */
    protected $filterSettingHelper;

    /** @var  Registry */
    protected $registry;

    public function __construct(
        Context $context,
        Registry $registry
    )
    {
        parent::__construct($context);
        $this->registry = $registry;
    }


    public function buildUrl(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter, $value)
    {
        $result = [];
        $data = $this->_request->getParam($filter->getRequestVar());
        if(!empty($data)){
            $values = explode('-',$data);
            foreach($values as $key=>$val){
                if(empty($val)){
                    unset($values[$key]);
                }
            }
            $key = array_search($value, $values);

            if($key !== false) {
                unset($values[$key]);
                $result = $values;
            }else{
                $result = $values;
                $result[] = $value;
            }
        } else {
            $result = [$value];
        }
        if(!empty($result)){
            $result = implode('-',$result);
        }else{
            $result = null;
        }

        $query = [];

        $query[$filter->getRequestVar()] = $result;

        return $this->_urlBuilder->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
    }

}
