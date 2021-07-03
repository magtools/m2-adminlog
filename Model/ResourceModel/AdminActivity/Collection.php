<?php

namespace Mtools\AdminLog\Model\ResourceModel\AdminActivity;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Mtools\AdminLog\Model\AdminActivity', 'Mtools\AdminLog\Model\ResourceModel\AdminActivity');
    }
}
