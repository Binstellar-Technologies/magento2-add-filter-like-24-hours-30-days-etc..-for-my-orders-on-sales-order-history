<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Binstellar\Orders\Block\Order;

use \Magento\Framework\App\ObjectManager;
use \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;

/**
 * Sales order history block
 *
 * @api
 * @since 100.0.2
 */
class History extends \Magento\Sales\Block\Order\History
{
    /**
     * @var string
     */
    protected $_template = 'Binstellar_Orders::order/history.phtml';

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_orderConfig;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected $orders;

    /**
     * @var CollectionFactoryInterface
     */
    private $orderCollectionFactory;

    protected $_storeManager;
    protected $request;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_orderConfig = $orderConfig;
        $this->_storeManager = $storeManager;
        $this->request = $request;
        parent::__construct($context, $orderCollectionFactory, $customerSession, $orderConfig,$data);
    }
       
    

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Orders'));
    }

    /**
     * Provide order collection factory
     *
     * @return CollectionFactoryInterface
     * @deprecated 100.1.1
     */
    private function getOrderCollectionFactory()
    {
        if ($this->orderCollectionFactory === null) {
            $this->orderCollectionFactory = ObjectManager::getInstance()->get(CollectionFactoryInterface::class);
        }
        return $this->orderCollectionFactory;
    }

    public function getAllOrder()
    {
        $cutId = $this->_customerSession->getCustomerId();
        $orderColl = [];
        if ($cutId) {
            $orderColl = $this->orderCollectionFactory->create();
            $orderColl->addAttributeToFilter('customer_id',$cutId);
        }
        return $orderColl;
    }


    /**
     * Get customer orders
     *
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrders()
    { 
        $filteroption = $this->getFilterOption();

        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }

        if (empty($filteroption) || ($filteroption == 0)) {
            if (!$this->orders) {
            $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'status',
                ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
            )->setOrder(
                'created_at',
                'desc'
            );
            }
        }else if($filteroption == 1){

            $prev_date = date('Y-m-d H:i:s', strtotime('-30 days'));
            $current_date = date('Y-m-d H:i:s');

            if (!$this->orders) {
            $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'status',
                ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
            )->addAttributeToFilter('created_at', ['gteq'=>$prev_date.' 00:00:00'])
            ->addAttributeToFilter('created_at', ['lteq'=>$current_date.' 23:59:59'])
            ->setOrder(
                'created_at',
                'desc'
            );
            }
        }else if($filteroption == 2){

            $prev_date = date('Y-m-d H:i:s', strtotime('-6 months'));
            $current_date = date('Y-m-d H:i:s');
            
            if (!$this->orders) {
            $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'status',
                ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
            )->addAttributeToFilter('created_at', ['gteq'=>$prev_date.' 00:00:00'])
            ->addAttributeToFilter('created_at', ['lteq'=>$current_date.' 23:59:59'])
            ->setOrder(
                'created_at',
                'desc'
            );
            }
        }else if($filteroption == 3){

            $prev_date = date('Y-m-d H:i:s', strtotime('-1 day'));
            $current_date = date('Y-m-d H:i:s');

            if (!$this->orders) {
            $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'status',
                ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
            )->addAttributeToFilter('created_at', ['gteq'=>$prev_date.' 00:00:00'])
            ->addAttributeToFilter('created_at', ['lteq'=>$current_date.' 23:59:59'])
            ->setOrder(
                'created_at',
                'desc'
            );
            }
        }

        // echo "<pre>";
        // print_r($this->orders->getData());die();

        return $this->orders;
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getOrders()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'sales.order.history.custompager'
            )->setCollection(
                $this->getOrders()
            );
            $this->setChild('pager', $pager);
            $this->getOrders()->load();
        }
        return $this;
    }

    /**
     * Get Pager child block output
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get order view URL
     *
     * @param object $order
     * @return string
     */
    public function getViewUrl($order)
    {
        return $this->getUrl('sales/order/view', ['order_id' => $order->getId()]);
    }

    /**
     * Get order track URL
     *
     * @param object $order
     * @return string
     * @deprecated 102.0.3 Action does not exist
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getTrackUrl($order)
    {
        //phpcs:ignore Magento2.Functions.DiscouragedFunction
        trigger_error('Method is deprecated', E_USER_DEPRECATED);
        return '';
    }

    /**
     * Get reorder URL
     *
     * @param object $order
     * @return string
     */
    public function getReorderUrl($order)
    {
        return $this->getUrl('sales/order/reorder', ['order_id' => $order->getId()]);
    }

    /**
     * Get customer account URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }

    /**
     * Get message for no orders.
     *
     * @return \Magento\Framework\Phrase
     * @since 102.1.0
     */
    public function getEmptyOrdersMessage()
    {
        return __('You have placed no orders.');
    }

    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function getFilterOption()
    {
        return $this->request->getParam('filter');
    }
    
}