<?php

namespace MT\Filter\Model\Request;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Search\RequestInterface;

class Builder
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Binder
     */
    private $binder;

    /**
     * @var array
     */
    private $data = [
        'dimensions' => [],
        'placeholder' => [],
    ];
    /**
     * @var Cleaner
     */
    private $cleaner;

    /** @var \Magento\Framework\App\Request\Http */
    private $httpRequest;

    public function __construct(
        ObjectManagerInterface $objectManager,
        \Magento\Framework\Search\Request\Config $config,
        \Magento\Framework\Search\Request\Binder $binder,
        \Magento\Framework\Search\Request\Cleaner $cleaner,
        \Magento\Framework\App\Request\Http $http
    )
    {
        $this->objectManager = $objectManager;
        $this->config = $config;
        $this->binder = $binder;
        $this->cleaner = $cleaner;
        $this->httpRequest = $http;
    }

    public function autoSetRequestName()
    {
        $module = $this->httpRequest->getControllerModule();
        switch ($module) {
            case 'Magento_CatalogSearch':
                $requestName = 'quick_search_container';
                break;
            default:
                $requestName = 'catalog_view_container';
        }
        $this->setRequestName($requestName);
    }

    /**
     * Set request name
     *
     * @param string $requestName
     * @return $this
     */
    public function setRequestName($requestName)
    {
        $this->data['requestName'] = $requestName;
        return $this;
    }

    /**
     * Set size
     *
     * @param int $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->data['size'] = $size;
        return $this;
    }

    /**
     * Set from
     *
     * @param int $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->data['from'] = $from;
        return $this;
    }

    /**
     * Bind dimension data by name
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function bindDimension($name, $value)
    {
        $this->data['dimensions'][$name] = $value;
        return $this;
    }

    /**
     * Bind data to placeholder
     *
     * @param string $placeholder
     * @param mixed $value
     * @return $this
     */
    public function bind($placeholder, $value,$key=false)
    {
        if($key === false){
            $this->data['placeholder']['$' . $placeholder . '$'] = $value;
            return $this;
        }

        if(strcmp($placeholder,'category_ids')==0){
            if(isset($this->data['placeholder']['$' . $placeholder . '$'])){
                $current = $this->data['placeholder']['$' . $placeholder . '$'];
                $this->data['placeholder']['$' . $placeholder . '$'] = $current.','.$value;
            }else{
                $this->data['placeholder']['$' . $placeholder . '$'] = $value;
            }
            return $this;
        }
        if(isset($this->data['placeholder']['$' . $placeholder . '$'])){
            $current = $this->data['placeholder']['$' . $placeholder . '$'];
                array_push($current,$value);
            $this->data['placeholder']['$' . $placeholder . '$'] = $current;
        }else{
            $this->data['placeholder']['$' . $placeholder . '$'] = [$value];
        }
        return $this;
    }

    public function removeData($code){
        if(isset($this->data['placeholder']['$'.$code.'$'])){
            unset($this->data['placeholder']['$'.$code.'$']);
        }
        return $this;
    }

    public function hasData($code)
    {
        return isset($this->data['placeholder']['$'.$code.'$']);
    }

    /**
     * Create request object
     *
     * @return RequestInterface
     */
    public function create()
    {
        if (!isset($this->data['requestName'])) {
            throw new \InvalidArgumentException("Request name not defined.");
        }
        $requestName = $this->data['requestName'];
        /** @var array $data */
        $data = $this->config->get($requestName);
        if ($data === null) {
            throw new \InvalidArgumentException("Request name '{$requestName}' doesn't exist.");
        }

        $data = $this->binder->bind($data, $this->data);
        $data = $this->cleaner->clean($data);

        $this->clear();

        return $this->convert($data);
    }

    /**
     * Clear data
     *
     * @return void
     */
    private function clear()
    {
        $this->data = [
            'dimensions' => [],
            'placeholder' => [],
        ];
    }

    /**
     * Convert array to Request instance
     *
     * @param array $data
     * @return RequestInterface
     */
    private function convert($data)
    {
        /** @var Mapper $mapper */
        $mapper = $this->objectManager->create(
            'Magento\Framework\Search\Request\Mapper',
            [
                'objectManager' => $this->objectManager,
                'rootQueryName' => $data['query'],
                'queries' => $data['queries'],
                'aggregations' => $data['aggregations'],
                'filters' => $data['filters']
            ]
        );
        return $this->objectManager->create(
            'Magento\Framework\Search\Request',
            [
                'name' => $data['query'],
                'indexName' => $data['index'],
                'from' => $data['from'],
                'size' => $data['size'],
                'query' => $mapper->getRootQuery(),
                'dimensions' => $this->buildDimensions(isset($data['dimensions']) ? $data['dimensions'] : []),
                'buckets' => $mapper->getBuckets()
            ]
        );
    }

    /**
     * @param array $dimensionsData
     * @return array
     */
    private function buildDimensions(array $dimensionsData)
    {
        $dimensions = [];
        foreach ($dimensionsData as $dimensionData) {
            $dimensions[$dimensionData['name']] = $this->objectManager->create(
                'Magento\Framework\Search\Request\Dimension',
                $dimensionData
            );
        }
        return $dimensions;
    }
}
