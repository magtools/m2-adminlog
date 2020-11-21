<?php

namespace Mtools\AdminLog\Controller\Adminhtml\File;

use Zend_Filter_BaseName;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Controller\Adminhtml\System;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\App\Filesystem\DirectoryList;

class Download extends System
{
    /**
     * @const acl
     */
    const ADMIN_RESOURCE = 'Mtools_AdminLog::logfiles_download';

    /**
     * @var Context $context
     * @var FileFactory $fileFactory
     */
    protected $fileFactory;

    /**
     * @param Context     $context
     * @param FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory
    ) {
        $this->fileFactory = $fileFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $filePath = $this->getFilePathWithFile($this->getRequest()->getParam('file'));

        $filter   = new Zend_Filter_BaseName();
        $fileName = $filter->filter($filePath);
        try {
            return $this->fileFactory->create(
                $fileName,
                [
                    'type'  => 'filename',
                    'value' => $filePath
                ]
            );
        } catch (\Exception $e) {
            throw new NotFoundException(__($e->getMessage()));
        }
    }

    /**
     * @param $filename
     * @return string
     */
    protected function getFilePathWithFile($fileName)
    {
        $path = DirectoryList::getDefaultConfig()[DirectoryList::LOG];

        return reset($path) . DS . $fileName;
    }
}
