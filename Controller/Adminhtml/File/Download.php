<?php

namespace Mtools\AdminLog\Controller\Adminhtml\File;

use Zend_Filter_BaseName;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
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
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param DirectoryList $directoryList
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        DirectoryList $directoryList
    ) {
        $this->fileFactory = $fileFactory;
        $this->directoryList = $directoryList;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
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
     * @param $fileName
     * @return string
     */
    protected function getFilePathWithFile($fileName)
    {
        $path = $this->directoryList->getDefaultConfig()[DirectoryList::LOG];
        return reset($path) . DS . $fileName;
    }
}
