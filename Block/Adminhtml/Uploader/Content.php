<?php

namespace Hevelop\FileUploader\Block\Adminhtml\Uploader;


/**
 * Class Content
 *
 * @package   Hevelop\FileUploader\Block\Adminhtml\Uploader
 * @author    Yuriy Boyko <yuriy@hevelop.com>
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License
 */
class Content extends \Magento\Cms\Block\Adminhtml\Wysiwyg\Images\Content
{

    /**
     * Files action source URL
     *
     * @return string
     */
    public function getContentsUrl()
    {
        return $this->getUrl('hevelop/uploader_files/contents', ['type' => $this->getRequest()->getParam('type')]);
    }

    /**
     * Javascript setup object for filebrowser instance
     *
     * @return string
     */
    public function getFilebrowserSetupObject()
    {
        $setupObject = new \Magento\Framework\DataObject();

        $setupObject->setData(
            [
                'newFolderPrompt' => __('New Folder Name:'),
                'deleteFolderConfirmationMessage' => __('Are you sure you want to delete this folder?'),
                'deleteFileConfirmationMessage' => __('Are you sure you want to delete this file?'),
                'targetElementId' => $this->getTargetElementId(),
                'contentsUrl' => $this->getContentsUrl(),
                'onInsertUrl' => $this->getOnInsertUrl(),
                'newFolderUrl' => $this->getNewfolderUrl(),
                'deleteFolderUrl' => $this->getDeletefolderUrl(),
                'deleteFilesUrl' => $this->getDeleteFilesUrl(),
                'headerText' => $this->getHeaderText(),
                'showBreadcrumbs' => true,
            ]
        );

        return $this->_jsonEncoder->encode($setupObject);
    }

    /**
     * New directory action target URL
     *
     * @return string
     */
    public function getNewfolderUrl()
    {
        return $this->getUrl('cms/wysiwyg_images/newFolder');
    }

    /**
     * Delete directory action target URL
     *
     * @return string
     */
    protected function getDeletefolderUrl()
    {
        return $this->getUrl('cms/wysiwyg_images/deleteFolder');
    }

    /**
     * Description goes here...
     *
     * @return string
     */
    public function getDeleteFilesUrl()
    {
        return $this->getUrl('cms/wysiwyg_images/deleteFiles');
    }

    /**
     * New directory action target URL
     *
     * @return string
     */
    public function getOnInsertUrl()
    {
        return $this->getUrl('cms/wysiwyg_images/onInsert');
    }

    /**
     * Target element ID getter
     *
     * @return string
     */
    public function getTargetElementId()
    {
        return $this->getRequest()->getParam('target_element_id');
    }

    protected function _construct()
    {
        parent::_construct();
        $this->buttonList->remove('insert_files');
    }
}
