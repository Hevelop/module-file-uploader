<?php

namespace Hevelop\FileUploader\Block\Adminhtml\Uploader;
/**
 * Class Tree
 *
 * @package   Hevelop\FileUploader\Block\Adminhtml\Uploader
 * @author    Yuriy Boyko <yuriy@hevelop.com>
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License
 */
class Tree extends \Magento\Cms\Block\Adminhtml\Wysiwyg\Images\Tree
{


    /**
     * Json source URL
     *
     * @return string
     */
    public function getTreeLoaderUrl()
    {
        return $this->getUrl('cms/wysiwyg_images/treeJson');
    }


}
