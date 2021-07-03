<?php

namespace Mtools\AdminLog\Observer;

use Magento\Customer\Model\Context;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Backend\Model\Auth\Session;

class ActionLog implements ObserverInterface
{
    protected $storeManager;

    protected $adminSession;

    /**
     * Admin ActionLog constructor.
     */
    public function __construct(
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\App\Response\Http $response,
        \Magento\Framework\UrlFactory $urlFactory,
        \Magento\Framework\App\Http\Context $context,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Session $adminSession
    )
    {
        $this->response = $response;
        $this->urlFactory = $urlFactory;
        $this->context = $context;
        $this->actionFlag = $actionFlag;
        $this->storeManager = $storeManager;
        $this->adminSession = $adminSession;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $request = $observer->getEvent()->getRequest();
        if ($request->getMethod() == 'POST') {
            $user = $this->adminSession->getUser();
            $actionUri = explode('/key/', $request->getRequestUri());
            $actionLog = [
                'module'     => $request->getControllerModule(),
                'route'      => $request->getRouteName(),
                'controller' => $request->getControllerName(),
                'action'     => $request->getActionName(),
                'handler'    => strtolower($request->getFullActionName()),
                'params'     => $request->getParams(),
                'uri'        => array_shift($actionUri),
                'email'      => $user->getEmail(),
                'user_id'    => $user->getUserId(),
                'user_name'  => $user->getUserName(),
            ];
            $tmp = $request;//die;
        }
    }
}
