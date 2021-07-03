<?php

namespace Mtools\AdminLog\Model;

use Magento\Cron\Exception;
use Magento\Framework\Model\AbstractModel;

class Contact extends AbstractModel
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Mtools\AdminLog\Model\ResourceModel\AdminActivity::class);
    }

}
