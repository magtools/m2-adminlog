<?php

namespace Mtools\AdminLog\Controller\Adminhtml\File;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Grid extends \Magento\Backend\App\Action
{
    /**
     * @const acl
     */
    const ADMIN_RESOURCE = 'Mtools_AdminLog::logfiles_view';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Magento_Backend::system');
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Log Files'));
        $this->_view->renderLayout();
    }
}
