<?php

namespace Hevelop\FileUploader\Controller\Adminhtml\Uploader;


/**
 * Class Upload
 *
 * @package   Hevelop\FileUploader\Controller\Uploader
 * @author    Yuriy Boyko <yuriy@hevelop.com>
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License
 */
class Index extends \Magento\Cms\Controller\Adminhtml\Wysiwyg\Images\Contents
{


    protected $resultPageFactory;


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;

        parent::__construct($context, $coreRegistry, $resultLayoutFactory, $resultJsonFactory);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->_initAction()->_saveSessionCurrentPath();

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("Hevelop File Manager"));
        return $resultPage;

    }
}