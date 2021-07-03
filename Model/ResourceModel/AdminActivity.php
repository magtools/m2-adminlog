<?php

namespace Mtools\AdminActivity\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AdminActivity extends AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('mtools_admin_activity', 'entity_id');
    }
}
