<?php

namespace Hevelop\FileUploader\Model;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Storage
 *
 * @package   Hevelop\FileUploader\Model
 * @author    Yuriy Boyko <yuriy@hevelop.com>
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License
 */
class Storage extends \Magento\Cms\Model\Wysiwyg\Images\Storage
{

    const EXTENSIONS_CONFIG = 'hevelop_uploader/general/supported_extensions';
    const EXTENSIONS_SCOPE = \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE;


    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;


    private $_allowedExtensions = [];


    public function __construct(
        \Magento\Backend\Model\Session $session,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Cms\Helper\Wysiwyg\Images $cmsWysiwygImages,
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDb,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Cms\Model\Wysiwyg\Images\Storage\CollectionFactory $storageCollectionFactory,
        \Magento\MediaStorage\Model\File\Storage\FileFactory $storageFileFactory,
        \Magento\MediaStorage\Model\File\Storage\DatabaseFactory $storageDatabaseFactory,
        \Magento\MediaStorage\Model\File\Storage\Directory\DatabaseFactory $directoryDatabaseFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $resizeParameters = [],
        array $extensions = [],
        array $dirs = [],
        array $data = []
    ) {
        $this->_session = $session;
        $this->_backendUrl = $backendUrl;
        $this->_cmsWysiwygImages = $cmsWysiwygImages;
        $this->_coreFileStorageDb = $coreFileStorageDb;
        $this->_directory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->_imageFactory = $imageFactory;
        $this->_assetRepo = $assetRepo;
        $this->_storageCollectionFactory = $storageCollectionFactory;
        $this->_storageFileFactory = $storageFileFactory;
        $this->_storageDatabaseFactory = $storageDatabaseFactory;
        $this->_directoryDatabaseFactory = $directoryDatabaseFactory;
        $this->_uploaderFactory = $uploaderFactory;
        $this->_resizeParameters = $resizeParameters;
        $this->_extensions = $extensions;
        $this->_dirs = $dirs;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct(
            $session,
            $backendUrl,
            $cmsWysiwygImages,
            $coreFileStorageDb,
            $filesystem,
            $imageFactory,
            $assetRepo,
            $storageCollectionFactory,
            $storageFileFactory,
            $storageDatabaseFactory,
            $directoryDatabaseFactory,
            $uploaderFactory,
            $resizeParameters,
            $extensions,
            $dirs,
            $data
        );
    }


    /**
     * Upload and resize new file
     *
     * @param string $targetPath Target directory
     * @param string $type       Type of storage, e.g. image, media etc.
     *
     * @return array File info Array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function uploadFile($targetPath, $type = null)
    {
        /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
        $uploader = $this->_uploaderFactory->create(['fileId' => 'image']);
        $allowedExtensions = $this->getAllowedFileExtensions();
        $uploader->setAllowedExtensions($allowedExtensions);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(false);
        $result = $uploader->save($targetPath);

        if (!$result) {
            throw new \Magento\Framework\Exception\LocalizedException(__('We can\'t upload the file right now.'));
        }

        if (in_array($this->getFileExtension($uploader->getUploadedFileName()), ['jpeg', 'png', 'gif', 'jpg'])) {
            // create thumbnail if image
            $this->resizeFile($targetPath . '/' . $uploader->getUploadedFileName(), true);
        }


        $result['cookie'] = [
            'name' => $this->getSession()->getName(),
            'value' => $this->getSession()->getSessionId(),
            'lifetime' => $this->getSession()->getCookieLifetime(),
            'path' => $this->getSession()->getCookiePath(),
            'domain' => $this->getSession()->getCookieDomain(),
        ];

        return $result;
    }


    public function getFileExtension($fileName)
    {
        return pathinfo($fileName, PATHINFO_EXTENSION);
    }

    public function getAllowedFileExtensions()
    {
        if (empty($this->_allowedExtensions)) {
            $config = $this->_scopeConfig->getValue(self::EXTENSIONS_CONFIG, self::EXTENSIONS_SCOPE);
            $this->_allowedExtensions = explode(",", $config);
            return $this->_allowedExtensions;
        }
        return $this->_allowedExtensions;

    }


    /**
     * @param string $path
     * @param mixed  $type
     *
     * @return \Magento\Framework\Data\Collection|\Magento\Framework\Data\Collection\Filesystem
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFilesCollection($path, $type = null)
    {
        if ($this->_coreFileStorageDb->checkDbUsage()) {
            $files = $this->_storageDatabaseFactory->create()->getDirectoryFiles($path);

            /** @var \Magento\MediaStorage\Model\File\Storage\File $fileStorageModel */
            $fileStorageModel = $this->_storageFileFactory->create();
            foreach ($files as $file) {
                $fileStorageModel->saveFile($file);
            }
        }

        $collection = $this->getCollection(
            $path
        )->setCollectDirs(
            false
        )->setCollectFiles(
            true
        )->setCollectRecursively(
            false
        )->setOrder(
            'mtime',
            \Magento\Framework\Data\Collection::SORT_ORDER_ASC
        );

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'zip'];

        $collection->setFilesFilter('/\.(' . implode('|', $allowed) . ')$/i');


        // prepare items
        foreach ($collection as $item) {
            $item->setId($this->_cmsWysiwygImages->idEncode($item->getBasename()));
            $item->setName($item->getBasename());
            $item->setShortName($this->_cmsWysiwygImages->getShortFilename($item->getBasename()));
            $item->setUrl($this->_cmsWysiwygImages->getCurrentUrl() . $item->getBasename());

            if ($this->isImage($item->getBasename())) {
                $thumbUrl = $this->getThumbnailUrl($item->getFilename(), true);
                // generate thumbnail "on the fly" if it does not exists
                if (!$thumbUrl) {
                    $thumbUrl = $this->_backendUrl->getUrl('cms/wysiwyg_images/thumbnail', ['file' => $item->getId()]);
                }

                $size = @getimagesize($item->getFilename());

                if (is_array($size)) {
                    $item->setWidth($size[0]);
                    $item->setHeight($size[1]);
                }
            } else {
                $thumbUrl = $this->_assetRepo->getUrl(self::THUMB_PLACEHOLDER_PATH_SUFFIX);
            }

            $item->setThumbUrl($thumbUrl);
        }

        return $collection;
    }
}
