<?php 
    /**
    * Simple Hello World Module 
    *
    * @category    QaisarSatti
    * @package     QaisarSatti_HelloWorld
    * @author      Muhammad Qaisar Satti
    * @Email       qaisarssatti@gmail.com
    *
    */

namespace QaisarSatti\HelloWorld\Block;

class BestSeller extends \Magento\Framework\View\Element\Template
{
	  protected $_coreRegistry = null;
    protected $_collectionFactory;
    protected $_productsFactory;

   public function __construct(
       \Magento\Backend\Block\Template\Context $context,
       \Magento\Framework\Registry $registry,
      \Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory $collectionFactory,
        \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $productsFactory,
       array $data = []
   ) {
  
       $this->_collectionFactory = $collectionFactory;
       $this->_coreRegistry = $registry;
       $this->_productsFactory = $productsFactory;
       parent::__construct($context, $data);
   }

   protected function _prepareLayout()
   {
        parent::_prepareLayout();
       $this->pageConfig->getTitle()->set(__('Best Seller'));

       if ($this->getBestSellerData()) {
            $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager','simple.pager')->setAvailableLimit(array(5=>5,10=>10,15=>15,20=>20));
            $pager->setLimit(5)->setShowPerPage(true);
            $pager->setCollection($this->getBestSellerData());
            $this->setChild('pager', $pager);
            $this->getBestSellerData()->load();
        }
        return $this;
   }
   public function getPagerHtml(){
        return $this->getChildHtml('pager');
    }
    public function getBestSellerData(){

      $page=($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;

      $pageSize=($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 10;
       $collection = $this->_collectionFactory->create()
                    ->setModel('Magento\Catalog\Model\Product')
                    ->setPeriod('month')

                   ;
      $bestSeller = $collection->getColumnValues('product_id');   

      $newCollection = $this->_productsFactory->create()->addAttributeToSelect('name')->addFieldToFilter('entity_id',array('in',$bestSeller))->setPageSize($pageSize)->setCurPage($page);   

       return $newCollection;
   }


}