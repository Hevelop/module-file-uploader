<?php

namespace Hevelop\FileUploader\Block\Adminhtml\Uploader;

use Hevelop\FileUploader\Model\Storage as FilesStorage;

/**
 * Class Files
 *
 * @package   Hevelop\FileUploader\Block\Adminhtml\Uploader
 * @author    Yuriy Boyko <yuriy@hevelop.com>
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License
 */
class Files extends \Magento\Cms\Block\Adminhtml\Wysiwyg\Images\Content\Files
{
    /**
     * Files collection object
     *
     * @var \Magento\Framework\Data\Collection\Filesystem
     */
    protected $_filesCollection;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Images\Storage
     */
    protected $_imageStorage;

    /**
     * @var \Magento\Cms\Helper\Wysiwyg\Images
     */
    protected $_imageHelper;

    /**
     * @var \Hevelop\FileUploader\Model\Storage
     */
    protected $_filesStorage;

    /**
     * Files constructor.
     *
     * @param \Magento\Backend\Block\Template\Context   $context
     * @param \Magento\Cms\Model\Wysiwyg\Images\Storage $imageStorage
     * @param \Magento\Cms\Helper\Wysiwyg\Images        $imageHelper
     * @param \Hevelop\FileUploader\Model\Storage       $filesStorage
     * @param array                                     $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Cms\Model\Wysiwyg\Images\Storage $imageStorage,
        \Magento\Cms\Helper\Wysiwyg\Images $imageHelper,
        FilesStorage $filesStorage,
        array $data = []
    ) {
        $this->_imageHelper = $imageHelper;
        $this->_imageStorage = $imageStorage;
        $this->_filesStorage = $filesStorage;
        parent::__construct($context, $imageStorage, $imageHelper, $data);
    }


    /**
     * @return \Magento\Framework\Data\Collection\Filesystem
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFiles()
    {
        if (!$this->_filesCollection) {
            $this->_filesCollection = $this->_filesStorage->getFilesCollection(
                $this->_imageHelper->getCurrentPath(),
                $this->_getMediaType()
            );
        }

        return $this->_filesCollection;
    }
}
